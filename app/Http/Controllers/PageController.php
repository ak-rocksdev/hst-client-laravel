<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Event;
use App\Models\Competition;
use App\Models\Sport;
use App\Models\Contestant;
use App\Models\Games;
use App\Models\Score;
use App\Models\CompetitionType;
use App\Models\Judge;
use App\Models\RunningList;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use Auth;

class PageController extends Controller
{
    public function index()
    {
        $events = Event::orderByDesc('end_date')
            ->limit(4)
            ->get();
        
        session()->forget('user-temp');
        return view('index', compact('events'));
    }

    public function viewEventsPage()
    {
        $auth = Auth::guard('web');
        $view = $auth->check() ? 'pages.user.events' : 'pages.events';
        $query = Event::where('type', 'competition')->latest('end_date');

        if($auth->check()) {
            // get filter request
            $filter = request()->query('filter');
            // if filter is 'all', get all events. else, get the events that the user has joined

            if($filter == 'all' || $filter == null) {
                $events = Event::where('type', 'competition')->latest('end_date')->get();
            } else {
                $events = Event::select('event_list.*')
                                ->leftJoin('competition_list', 'competition_list.ID_event', '=', 'event_list.ID_event')
                                ->leftJoin('participant', 'participant.ID_competition', '=', 'competition_list.ID_competition')
                                ->where('participant.ID_user', $auth->user()->ID_user)
                                ->where('event_list.type', 'competition')
                                ->orderByDesc('event_list.start_date')
                                ->distinct()
                                ->get();
            }
        } else {
            $events = $query->get();
        }

        return view($view, compact('events'));
    }

    public function viewEventPageById($id)
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

    public function viewMyEventDetailMemberPage($id)
    {
        $event = Event::find($id);
        $auth = Auth::guard('web');

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

        $isJudgeForThisEvent = Judge::leftJoin('competition_list', 'judge_list.ID_competition', '=', 'competition_list.ID_competition')
                                    ->leftJoin('event_list', 'competition_list.ID_event', '=', 'event_list.ID_event')
                                    ->where('judge_list.ID_user', $auth->user()->ID_user)
                                    ->where('event_list.ID_event', $id)
                                    ->exists();
        return view('pages.user.event-detail-member', compact('event', 'competitions', 'sports', 'contestants', 'groupedCompetitions', 'isJudgeForThisEvent'));
    }

    public function viewEventDetailJudgePage($id)
    {
        $event = Event::find($id);
        $loggedInUserId = Auth::guard('web')->user()->ID_user; 
        $isJudgeForThisEvent = Judge::leftJoin('competition_list', 'judge_list.ID_competition', '=', 'competition_list.ID_competition')
                                    ->leftJoin('event_list', 'competition_list.ID_event', '=', 'event_list.ID_event')
                                    ->where('judge_list.ID_user', $loggedInUserId)
                                    ->where('event_list.ID_event', $id)
                                    ->exists();
        if(!$isJudgeForThisEvent) {
            return abort(404);
        }
        $competitions = Competition::select('competition_list.*', 'sport.name as sports', 'judge_list.ID_user')
                                    ->join('sport', 'competition_list.sport', '=', 'sport.ID_sport')
                                    ->leftJoin('event_list', 'competition_list.ID_event', '=', 'event_list.ID_event')
                                    ->leftJoin('judge_list', 'competition_list.ID_competition', '=', 'judge_list.ID_competition')
                                    ->where('event_list.ID_event', $id)
                                    ->where('judge_list.ID_user', $loggedInUserId)
                                    ->orderBy('sport')
                                    ->get();
        
        // $contestants = Contestant::leftJoin('competition_list', 'contestant_list.ID_competition', 'competition_list.ID_competition')
        //                         ->leftJoin('user', 'contestant_list.ID_user', 'user.ID_user')
        //                         ->leftJoin('user_origin', 'user.ID_user', 'user_origin.user_id')
        //                         ->leftJoin('sport', 'competition_list.sport', '=', 'sport.ID_sport')
        //                         ->leftJoin('event_list', 'competition_list.ID_event', '=', 'event_list.ID_event')
        //                         ->leftJoin('judge_list', 'competition_list.ID_competition', '=', 'judge_list.ID_competition')
        //                         ->where('competition_list.ID_competition', $id)
        //                         ->select('contestant_list.*',
        //                             'competition_list.level as competition_name',
        //                             'sport.name as sport_name',
        //                             'event_list.name as event_name',
        //                             'user.full_name', 
        //                             'user_origin.indo_province_name',
        //                             'user_origin.indo_city_name',
        //                             'user_origin.state_name',
        //                             'user_origin.country_name',
        //                         )
        //                         ->orderByDesc('sport.name')
        //                         ->orderByDesc('contestant_list.created_at')
        //                         ->get();
        //                         return dd($contestants);
        return view('pages.user.judge-home', compact('event', 'competitions'));
    }

    public function viewJudgeScoringPage($runningId)
    {
        $loggedInUserId = Auth::guard('web')->user();
        
        $contestant = DB::table('running_list')
                        ->leftJoin('contestant_list', 'running_list.ID_contestant', '=', 'contestant_list.ID_contestant')
                        ->leftJoin('competition_list', 'contestant_list.ID_competition', '=', 'competition_list.ID_competition')
                        ->leftJoin('user', 'contestant_list.ID_user', '=', 'user.ID_user')
                        ->leftJoin('user_origin', 'user.ID_user', '=', 'user_origin.user_id')
                        ->leftJoin('event_list', 'competition_list.ID_event', '=', 'event_list.ID_event')
                        ->leftJoin('games', 'running_list.ID_games', '=', 'games.ID_games')
                        ->where('running_list.ID_running', $runningId)
                        // ->where('running_list.status', 0)
                        ->select(
                            'running_list.*',
                            DB::raw('CASE WHEN games.ID_type = 1 THEN "Qualification" WHEN games.ID_type = 2 THEN "Semi-final" WHEN games.ID_type = 3 THEN "Final" END AS round_name'),
                            'event_list.name as event_name',
                            'contestant_list.*',
                            'competition_list.level as competition_level',
                            'competition_list.ID_event',
                            'user.*',
                            DB::raw('IF(user.nick_name IS NULL, SUBSTRING_INDEX(user.full_name, " ", 1), user.nick_name) as nick_name'),
                            'user_origin.*',
                            DB::raw('TIMESTAMPDIFF(YEAR, user.dateofbirth, CURDATE()) AS age')
                        )
                        ->first();

        // Get the next running id
        // get type from current games, put "where" by type.
        $IDtypeFromCurrentGames = Games::select('ID_type')
                                            ->where('ID_games', $contestant->ID_games)
                                            ->first();


        $nextRunningId = RunningList::leftJoin('games', 'running_list.ID_games', 'games.ID_games')
                            ->where('ID_running', '>', $runningId)
                            ->where('running_list.ID_games', $contestant->ID_games)
                            ->where('games.ID_type', $IDtypeFromCurrentGames->ID_type)
                            ->orderBy('running_list.increment')
                            ->orderBy('running_list.ID_running')
                            ->first();
                            // return dd($nextRunningId);
        // do make the group value first on the database, to be used for the query above
        
        $judge = Judge::leftJoin('user', 'judge_list.ID_user', '=', 'user.ID_user')
                        ->where('judge_list.ID_user', $loggedInUserId->ID_user)
                        ->where('judge_list.ID_competition', $contestant->ID_competition)
                        ->first();
        $isHeadJudge = $judge->isHeadJudge == 1 ? true : false;

        return view('pages.user.judge-scoring', compact('contestant', 'judge', 'isHeadJudge', 'nextRunningId'));
    }

    public function viewCheckInPage($id)
    {
        $event = Event::find($id);
        $auth = Auth::guard('web');

        $competitions = Competition::select('competition_list.*', 'sport.name as sports')
            ->join('sport', 'competition_list.sport', '=', 'sport.ID_sport')
            ->where('ID_event', $id)
            ->orderBy('sport')
            ->get();

        // get competition list, grouped by competition list level, with the contestant list for each competition

        $competitionList = Competition::leftJoin('event_list', 'competition_list.ID_event', '=', 'event_list.ID_event')
                ->leftJoin('contestant_list', 'competition_list.ID_competition', '=', 'contestant_list.ID_competition')
                ->leftJoin('sport', 'competition_list.sport', '=', 'sport.ID_sport')
                ->where('event_list.ID_event', $id)
                ->groupBy('sport.name', 'competition_list.level', 'competition_list.ID_competition')
                ->orderBy('competition_list.ID_competition')
                ->select(
                    'sport.name as sports',
                    'competition_list.level',
                    'competition_list.ID_competition',
                    DB::raw('COUNT(CASE WHEN contestant_list.attendance = 1 THEN 1 END) as total_attendance'),
                    DB::raw('COUNT(contestant_list.ID_contestant) as total_participant')
                )
                ->get();

        
        $groupedCompetitions = [];
        foreach ($competitionList as $competition) {
            if (!isset($groupedCompetitions[$competition->sports])) {
                $groupedCompetitions[$competition->sports] = [
                    'sport' => $competition->sports,
                    'data' => [],
                ];
            }
            
            $groupedCompetitions[$competition->sports]['data'][] = $competition;
        }
        
        $groupedCompetitions = array_values($groupedCompetitions);

        // return dd($groupedCompetitions);
        // $sports = Competition::select('sport.name as sports')
        //     ->join('sport', 'competition_list.sport', '=', 'sport.ID_sport')
        //     ->where('ID_event', $id)
        //     ->distinct()
        //     ->select('sport.*')
        //     ->get();

        // $contestants = Contestant::leftJoin('competition_list', 'contestant_list.ID_competition', 'competition_list.ID_competition')
        //                 ->leftJoin('user', 'contestant_list.ID_user', 'user.ID_user')
        //                 ->leftJoin('user_origin', 'user.ID_user', 'user_origin.user_id')
        //                 ->leftJoin('sport', 'competition_list.sport', '=', 'sport.ID_sport')
        //                 ->leftJoin('event_list', 'competition_list.ID_event', '=', 'event_list.ID_event')
        //                 ->where('competition_list.ID_event', $id)
        //                 ->select('contestant_list.*',
        //                     'competition_list.level as competition_name',
        //                     'sport.name as sport_name',
        //                     'event_list.name as event_name',
        //                     'user.full_name', 
        //                     'user_origin.indo_province_name',
        //                     'user_origin.indo_city_name',
        //                     'user_origin.state_name',
        //                     'user_origin.country_name',
        //                 )
        //                 ->orderByDesc('sport.name')
        //                 ->orderByDesc('contestant_list.created_at')
        //                 ->get();

        return view('pages.user.check-in', compact('event', 'competitions', 'groupedCompetitions'));
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

        $userOrigin = DB::table('user_origin')
            ->where('user_id', $user->ID_user)
            ->first();

        $user->country_id = $userOrigin->country_id ?? null;
        return view('pages.user.edit-profile', compact('user', 'userOrigin'));
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