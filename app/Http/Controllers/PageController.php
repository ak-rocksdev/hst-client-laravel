<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Event;
use App\Models\Competition;
use App\Models\Sport;
use App\Models\Contestant;
use App\Models\Games;
use App\Models\Score;
use App\Models\CompetitionType;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use Auth;

class PageController extends Controller
{
    public function index()
    {
        $events = Event::orderByDesc('create_date')
            ->limit(4)
            ->get();
        
        session()->forget('user-temp');
        return view('index', compact('events'));
    }

    public function eventPageById($id)
    {
        $event = Event::find($id);

        if (!isset($event)) {
            return abort(404);
        }

        // check if today is in between start_registration date and end_registration date
        $startRegistration = new \DateTime($event->start_registration);
        $endRegistration = new \DateTime($event->end_registration);

        $today = new \DateTime(); // Current date and time

        $isRegistrationOpen = $today >= $startRegistration && $today <= $endRegistration;

        $competitions = Competition::select('competition_list.*', 'sport.name as sports')
            ->join('sport', 'competition_list.sport', '=', 'sport.ID_sport')
            ->where('ID_event', $id)
            ->orderBy('sport')
            ->get();

        $groupedCompetitions = [];
        foreach ($competitions as $competition) {
            if (!isset($groupedCompetitions[$competition->sports])) {
                $groupedCompetitions[$competition->sports] = [
                    'sports' => $competition->sports,
                    'levels' => [],
                ];
            }
            
            $groupedCompetitions[$competition->sports]['levels'][] = $competition->level;
        }
        
        $groupedCompetitions = array_values($groupedCompetitions);
        // return dd($groupedCompetitions);
        
        $sports = Competition::select('sport.name as sports')
            ->join('sport', 'competition_list.sport', '=', 'sport.ID_sport')
            ->where('ID_event', $id)
            ->distinct()
            ->select('sport.*')
            ->get();

        $contestants = Contestant::leftJoin('competition_list', 'contestant_list.ID_competition', 'competition_list.ID_competition')
                        ->leftJoin('user', 'contestant_list.ID_user', 'user.ID_user')
                        ->leftJoin('user_origin', 'user.ID_user', 'user_origin.user_id')
                        ->leftJoin('sport', 'competition_list.sport', '=', 'sport.ID_sport')
                        ->leftJoin('event_list', 'competition_list.ID_event', '=', 'event_list.ID_event')
                        ->where('competition_list.ID_event', $id)
                        ->select('contestant_list.*',
                            'competition_list.level as competition_name',
                            'sport.name as sport_name',
                            'event_list.name as event_name',
                            'user.full_name', 
                            'user_origin.indo_province_name',
                            'user_origin.indo_city_name',
                            'user_origin.state_name',
                            'user_origin.country_name',
                        )
                        ->orderByDesc('sport.name')
                        ->orderByDesc('contestant_list.created_at')
                        ->get();

        return view('pages.event', compact('event', 'competitions', 'sports', 'contestants', 'isRegistrationOpen', 'groupedCompetitions'));
    }

    public function viewUserLoginPage(Request $request){
        $isLoggedIn = Auth::guard('web')->check();
        $urlReferer = $request->headers->get('referer');
        $parsedUrl = parse_url($urlReferer);
        
        $previousURL = $parsedUrl['path'];
        if($previousURL != '/update-password') {
            session(['url.intended' => $previousURL]);
        }
        if(!$isLoggedIn) {
            return view('pages.login');
        } else {
            return redirect()->back();
        }
    }

    public function viewUserRegisterPage(){
        $isLoggedIn = Auth::guard('web')->check();
        if(!$isLoggedIn) {
            return view('pages.register');
        } else {
            return redirect()->back();
        }
    }

    public function viewUpdatePasswordPage() {
        $findUserByEmail = session('user-temp');
        if(!isset($findUserByEmail)) {
            return redirect()->back();
        }
        return view('pages.update-password', compact('findUserByEmail'));
    }

    public function viewProfilePage() {
        $user = Auth::guard('web')->user();

        // get the events that the user has joined include the score_list
        $events = Event::select('event_list.*')
            ->leftJoin('competition_list', 'event_list.ID_event', '=', 'competition_list.ID_event')
            ->leftJoin('contestant_list', 'competition_list.ID_competition', '=', 'contestant_list.ID_competition')
            ->where('contestant_list.ID_user', $user->ID_user)
            ->where('contestant_list.attendance', 1)
            ->orderByDesc('event_list.create_date')
            ->distinct()
            ->get();

        $userEvents = DB::table('event_list as e')
            ->join('competition_list as c', 'c.ID_event', '=', 'e.ID_event')
            ->join('games as g', 'g.ID_competition', '=', 'c.ID_competition')
            ->join('score_list as s', 's.ID_games', '=', 'g.ID_games')
            ->join('contestant_list as con', 's.ID_contestant', '=', 'con.ID_contestant') // Using the 'contestant_list' table
            ->join('competition_type as ct', 'ct.ID_type', '=', 'g.ID_type')
            ->where('con.ID_user', $user->ID_user)
            ->where('con.attendance', 1)
            ->orderBy('e.start_date', 'desc')
            ->orderBy('ct.ID_type', 'desc')
            ->select('e.name as event_name', 'e.start_date', 'c.ID_competition', 'g.ID_type', 'ct.name as round_name', 's.score')
            ->get();

        $scores = DB::table('score_list as s')
            ->join('games as g', 's.ID_games', '=', 'g.ID_games')
            ->join('competition_list as c', 'g.ID_competition', '=', 'c.ID_competition')
            ->join('event_list as e', 'c.ID_event', '=', 'e.ID_event')
            ->join('contestant_list as con', 's.ID_contestant', '=', 'con.ID_contestant')
            ->where('s.fixed', 0)
            ->where('con.ID_user', $user->ID_user)
            ->where('con.attendance', 1)
            ->orderBy('e.start_date', 'desc')
            ->orderByDesc('g.ID_type')
            ->orderByDesc('s.score')
            ->get(['e.name as event_name', 'c.level as level', 'e.ID_event', 'e.start_date', 'c.ID_competition', 'g.ID_type', 's.score', 'c.qualification', 'c.final']);;
        // return dd($scores);
        $processedScores = [];

        foreach ($scores as $score) {
            $key = $score->event_name . '_' . $score->ID_competition . '_' . $score->ID_type;

            if (!isset($processedScores[$key])) {
                $processedScores[$key] = [
                    'event_name' => $score->event_name,
                    'event_category' => $score->level,
                    'start_date' => $score->start_date,
                    'ID_event' => $score->ID_event,
                    'ID_competition' => $score->ID_competition,
                    'ID_type' => $score->ID_type,
                    'total_score' => 0,
                    'count' => 0
                ];
            }

            $limit = ($score->ID_type == 1) ? $score->qualification : $score->final;
            if ($processedScores[$key]['count'] < $limit) {
                $processedScores[$key]['total_score'] += number_format($score->score, 2);
                $processedScores[$key]['ID_type'] = $score->ID_type == 1 ? 'Qualification' : 'Final';
                $processedScores[$key]['count']++;
            }
        }
        
        // $topScorer = DB::table('score_list as s')
        //     ->join('contestant_list as c', 's.ID_contestant', '=', 'c.ID_contestant')
        //     ->join('user as u', 'c.ID_user', '=', 'u.ID_user')
        //     ->select('u.ID_user', 'u.full_name', DB::raw('count(s.ID_score) as score_count'))
        //     ->groupBy('u.ID_user', 'u.full_name')
        //     ->orderBy('score_count', 'desc')
        //     ->get();
        // return dd($user);

        return view('pages.user.profile', compact('user', 'events', 'processedScores'));
    }

    public function viewEditProfilePage() {
        $user = Auth::guard('web')->user();
        return view('pages.user.edit-profile', compact('user'));
    }
}





// $competitionId = $RowSQL['ID_competition'];

// $results = User::select('user.full_name', 
//     \DB::raw("CASE WHEN country_id = 'ID' THEN user_origin.indo_province_name ELSE user_origin.state_name END AS state_name"),
//     \DB::raw("CASE WHEN country_id <> 'ID' THEN user_origin.city_name ELSE user_origin.indo_city_name END AS city_name")
// )
// ->join('contestant_list', 'user.ID_user', '=', 'contestant_list.ID_user')
// ->leftJoin('user_origin', 'user.ID_user', '=', 'user_origin.user_id')
// ->where('contestant_list.ID_competition', $competitionId)
// ->get();


// Nge-loop data dari peserta yang sudah register utk halaman event

// use App\Models\CompetitionList;
// use App\Models\Sport;

// $id = ...; // Replace this with the value of $id

// $results = CompetitionList::select('competition_list.*', 'sport.name as sports')
//     ->join('sport', 'competition_list.sport', '=', 'sport.ID_sport')
//     ->where('ID_event', $id)
//     ->get();





// Score List Query

// use App\Models\ScoreList;
// use App\Models\ContestantList;
// use App\Models\User;
// use App\Models\CompetitionList;

// $RowSQLIdCompetition = ...; // Replace this with the value of $RowSQL['ID_competition']

// $ResultSQL1 = ScoreList::selectRaw("SUM(score) as score, GROUP_CONCAT(score) as aa, ID_contestant, full_name")
//     ->fromSub(function ($query) use ($RowSQLIdCompetition) {
//         $query->select('c.ID_contestant', 'd.full_name', 'sub_table.score')
//             ->fromSub(function ($query) {
//                 $query->select('ID_contestant', 'score', 'ID_judge', \DB::raw('@rn:=CASE WHEN @ID_contestant = ID_contestant THEN @rn + 1 ELSE 1 END AS rn'), \DB::raw('@ID_contestant:=ID_contestant'))
//                     ->fromSub(function ($query) use ($RowSQLIdCompetition) {
//                         $query->select('*')
//                             ->from('score_list')
//                             ->whereIn('ID_contestant', function ($query) {
//                                 $query->select('ID_contestant')
//                                     ->from('score_list');
//                             })
//                             ->where('ID_judge', 0)
//                             ->whereIn('ID_games', function ($query) use ($RowSQLIdCompetition) {
//                                 $query->select('ID_games')
//                                     ->from('games')
//                                     ->whereIn('ID_type', function ($query) use ($RowSQLIdCompetition) {
//                                         $query->select('ID_type')
//                                             ->from('competition_type')
//                                             ->where('name', 'Final')
//                                             ->where('ID_competition', $RowSQLIdCompetition);
//                                     });
//                             })
//                             ->orderBy('ID_contestant')
//                             ->orderByDesc('score');
//                     }, 'a')
//                     ->leftJoin(\DB::raw('(SELECT @ID_contestant:=NULL, @rn:=NULL) vars'), \DB::raw('1'), '=', \DB::raw('1'));
//             }, 'sub_table')
//             ->join('contestant_list as c', 'c.ID_contestant', '=', 'sub_table.ID_contestant')
//             ->join('user as d', 'c.ID_user', '=', 'd.ID_user')
//             ->join('competition_list as e', 'c.ID_competition', '=', 'e.ID_competition')
//             ->whereRaw('rn <= e.final')
//             ->where('c.attendance', '1')
//             ->orderBy('ID_contestant')
//             ->orderByDesc('score');
//     }, 'dd')
//     ->groupBy('ID_contestant')
//     ->orderByDesc('score')
//     ->limit(10)
//     ->get();

// $i = 1;

// $point = [25, 20, 16, 13, 11, 10, 9, 8, 7, 6];





// kolom point di tabel score_list (original)

// @php
//     $ID_games = array();
//     $ResultSQLGames = $LinkSQL->query("SELECT CONCAT(code, rn) as code, bb.ID_games FROM ( SELECT aa.*, @rn:=CASE WHEN @types=I D_type THEN @rn + 1 ELSE 1 END AS rn, @types:=ID_type FROM (SELECT @types:=NULL, @rn:=NULL) vars, (SELECT a.*, b.code FROM games a INNER JOIN competition_type b ON a.ID_type=b .ID_type WHERE a.ID_games IN (SELECT ID_games FROM score_list WHERE ID_games IN (SELECT ID_games FROM games WHERE ID_competition='" . $RowSQL[' ID_competition '] ."')) ORDER BY ID_type ASC)aa)bb ");
//     while($RowSQLGames = $ResultSQLGames->fetch_array(MYSQLI_ASSOC)){
//         echo '<th class="titleContent text-center ">' . $RowSQLGames['code'] . '</th>';
//         array_push($ID_games, $RowSQLGames['ID_games']);
//     }
// @endphp

// Convert to laravel query

// use App\Models\Game;
// use App\Models\CompetitionType;

// $RowSQLIdCompetition = ...; // Replace this with the value of $RowSQL['ID_competition']

// $results = Game::select(\DB::raw("CONCAT(code, rn) as code"), 'bb.ID_games')
//     ->fromSub(function ($query) use ($RowSQLIdCompetition) {
//         $query->select('aa.*', \DB::raw('@rn:=CASE WHEN @types=ID_type THEN @rn + 1 ELSE 1 END AS rn'), \DB::raw('@types:=ID_type'))
//             ->fromSub(function ($query) use ($RowSQLIdCompetition) {
//                 $query->select('a.*', 'b.code')
//                     ->from('games as a')
//                     ->join('competition_type as b', 'a.ID_type', '=', 'b.ID_type')
//                     ->whereIn('a.ID_games', function ($query) use ($RowSQLIdCompetition) {
//                         $query->select('ID_games')
//                             ->from('score_list')
//                             ->whereIn('ID_games', function ($query) use ($RowSQLIdCompetition) {
//                                 $query->select('ID_games')
//                                     ->from('games')
//                                     ->where('ID_competition', $RowSQLIdCompetition);
//                             });
//                     })
//                     ->orderBy('a.ID_type', 'ASC');
//             }, 'aa')
//             ->leftJoin(\DB::raw('(SELECT @types:=NULL, @rn:=NULL) vars'), \DB::raw('1'), '=', \DB::raw('1'));
//     }, 'bb')
//     ->get();





// $ID_games = [];
//         $ID_competition = $competitions[0]->ID_competition;
            
//         // Retrieve the games with their corresponding competition types
//         $games = Games::select('games.*', 'competition_type.code')
//                 ->join('competition_type', 'games.ID_type', '=', 'competition_type.ID_type')
//                 ->whereIn('games.ID_games', function ($query) use ($ID_competition) {
//                     $query->select('ID_games')
//                         ->from('score_list')
//                         ->whereIn('ID_games', function ($query) use ($ID_competition) {
//                             $query->select('ID_games')
//                                 ->from('games')
//                                 ->where('ID_competition', $ID_competition);
//                         });
//                 })
//                 ->orderBy('competition_type.ID_type')
//                 ->get();
        

//         $competitionName = 'Final'; // Change this to the specific competition name you want

//         $contestantsList = Contestant::selectRaw('SUM(score) as score, GROUP_CONCAT(score) as aa, contestant_list.ID_contestant, MAX(full_name) as full_name')
//         ->join(DB::raw('(SELECT ID_contestant, score, ID_judge, 
//             @rn:=CASE WHEN @ID_contestant = ID_contestant THEN @rn + 1 ELSE 1 END AS rn,
//             @ID_contestant:=ID_contestant
//             FROM (SELECT @ID_contestant:=NULL, @rn:=NULL) vars, 
//             (SELECT * FROM score_list
//             WHERE ID_contestant IN (SELECT ID_contestant FROM score_list) 
//             AND ID_judge = 0 
//             AND ID_games IN (SELECT ID_games FROM games 
//                 WHERE ID_type IN (SELECT ID_type FROM competition_type WHERE name = ?) 
//                 AND ID_competition = ?)
//             ORDER BY ID_contestant, score DESC) a) sub_table'), 
//             function($join) {
//                 $join->on('contestant_list.ID_contestant', '=', 'sub_table.ID_contestant');
//             })
//         ->leftJoin('contestant_list as c', 'c.ID_contestant', '=', 'sub_table.ID_contestant')
//         ->leftJoin('user as d', 'c.ID_user', '=', 'd.ID_user')
//         ->leftJoin('competition_list as e', 'c.ID_competition', '=', 'e.ID_competition')
//         ->whereRaw('rn <= e.final')
//         ->where('c.attendance', 1)
//         ->orderBy('sub_table.ID_contestant')
//         ->orderByDesc('sub_table.score')
//         ->groupBy('contestant_list.ID_contestant')
//         ->orderByDesc('score')
//         ->limit(10)
//         ->get();

//         $ID_games = Games::whereIn('ID_games', function ($query) use ($ID_competition) {
//             $query->select('ID_games')
//                 ->from('score_list')
//                 ->whereIn('ID_games', function ($subquery) use ($ID_competition) {
//                     $subquery->select('ID_games')
//                         ->from('games')
//                         ->where('ID_competition', $competitions[0]->ID_competition); // Make sure $RowSQL is defined
//                 });
//         })->pluck('ID_games')->toArray();

//         $point = [25, 20, 16, 13, 11, 10, 9, 8, 7, 6];