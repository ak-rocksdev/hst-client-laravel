<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Competition;
use App\Models\User;
use App\Models\Event;
use App\Models\Contestant;
use App\Models\Games;
use App\Models\Score;

use DB;

use Auth;

use App\Http\Requests\CreateContestantRequest;

class ApiController extends Controller
{
    public function doConfirmCompetitionOnRegister(Request $request)
    {
        $competitions = [];
        foreach($request->competitionId as $competitionId) {
            $competition = Competition::where('ID_competition', $competitionId)
                ->leftJoin('sport', 'sport.ID_sport', 'competition_list.sport')
                ->select('competition_list.*', 'sport.name as sport_name')
                ->first();
            if($competition) {
                array_push($competitions, $competition);
            }
        }
        $userRegister = User::where('ID_user', $request->userId)->first();
        $event = Event::where('ID_event', $request->eventId)->first();

        $data = [
            'competitions' => $competitions,
            'userRegister' => $userRegister,
            'event' => $event
        ];

        return response()->json([
            'status' => 'success',
            'data' => $data,
            'code' => 200
        ], 200);
    }

    public function registerContestantByCompetitionId(CreateContestantRequest $request) {
        $data = $request->validated();

        foreach($data['ID_competition'] as $competitionId) {
            $data['ID_competition'] = $competitionId;
            $data['attendance'] = 0;
            $data['insert_user'] = 0;
            Contestant::create($data);
        }
        // Contestant::create($data);
        return response()->json([
            'status' => 'success',
            'data' => $data,
            'code' => 200
        ], 200);
    }

    public function getEventStatusByUserId(Request $request) {
        $loggedInUser = Auth::user();
        $user = User::where('ID_user', $loggedInUser->ID_user)->first();
        $event = Event::where('ID_event', $request->ID_event)->first();

        // check if today is in between start_registration date and end_registration date
        $startRegistration = $event->start_registration;
        $endRegistration = $event->end_registration;

        $today = new \DateTime(); // Current date and time
        $start = new \DateTime($startRegistration);
        $end = new \DateTime($endRegistration);

        if ($today >= $start && $today <= $end) {
            $maxJoinCompetition = $event->max_join_competition;

            // check on Contestant table if user with current ID_user and current event is already registered
            $contestant = Contestant::where('ID_user', $user->ID_user)
                ->join('competition_list', 'competition_list.ID_competition', 'contestant_list.ID_competition')
                ->where('competition_list.ID_event', $event->ID_event)
                ->get();

            $data = [
                'user_count' => $contestant->count(),
                'remaining_slots' => $maxJoinCompetition - $contestant->count()
            ];

            return response()->json([
                'status' => 'success',
                'data' => $data,
                'code' => 200
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'messages' => [
                    '0' => __('messages.response_event_date_invalid')
                ],
                'code' => 400
            ], 400);
        }
    }

    public function getRegisteredContestantByCompetitionId(Request $request) {
        $request->ID_competition = $request->ID_competition ?? Competition::where('ID_event', $request->ID_event)->first()->ID_competition;

        $competition = Competition::where('ID_competition', $request->ID_competition)
            ->leftJoin('sport', 'sport.ID_sport', 'competition_list.sport')
            ->leftJoin('event_list', 'event_list.ID_event', 'competition_list.ID_event')
            ->select('competition_list.*', 'sport.name as sport_name', 'event_list.name as event_name')
            ->first();
        // $contestants = Contestant::where('ID_competition', $competition->ID_competition)
        //     ->join('user', 'user.ID_user', 'contestant_list.ID_user')
        //     ->join('user_origin', 'user_origin.user_id', 'user.ID_user')
        //     ->select('contestant_list.*', 'user.full_name', 'user_origin.indo_province_name', 'user_origin.indo_city_name', 'user_origin.state_name', 'user_origin.country_name')
        //     ->get();

        $contestants = Contestant::leftJoin('competition_list', 'contestant_list.ID_competition', 'competition_list.ID_competition')
                    ->leftJoin('user', 'contestant_list.ID_user', 'user.ID_user')
                    ->leftJoin('user_origin', 'user.ID_user', 'user_origin.user_id')
                    ->leftJoin('event_list', 'competition_list.ID_event', '=', 'event_list.ID_event')
                    ->where('competition_list.ID_competition', $competition->ID_competition)
                    ->select('contestant_list.*',
                        'competition_list.level as competition_name',
                        'event_list.name as event_name',
                        'user.full_name', 
                        'user_origin.indo_province_name',
                        'user_origin.indo_city_name',
                        'user_origin.state_name',
                        'user_origin.country_name',
                    )
                    ->orderByDesc('contestant_list.created_at')
                    ->get();
        $data = [
            'competition' => $competition,
            'contestants' => $contestants
        ];

        return response()->json([
            'status' => 'success',
            'data' => $data,
            'code' => 200
        ], 200);
    }

    public function getResultByCompetitionId(Request $request) {
        $request->ID_competition = $request->ID_competition ?? Competition::where('ID_event', $request->ID_event)->first()->ID_competition;
        
        // Fetch the competition data
        $competition = Competition::where('ID_competition', $request->ID_competition)
            ->leftJoin('sport', 'sport.ID_sport', 'competition_list.sport')
            ->leftJoin('event_list', 'event_list.ID_event', 'competition_list.ID_event')
            ->select('competition_list.*', 'sport.name as sport_name', 'event_list.name as event_name')
            ->first();
        $contestants = Contestant::leftJoin('competition_list', 'contestant_list.ID_competition', 'competition_list.ID_competition')
                        ->leftJoin('score_list', 'contestant_list.ID_contestant', 'score_list.ID_contestant')
                        ->leftJoin('games', 'score_list.ID_games', 'games.ID_games')
                        ->leftJoin('user', 'contestant_list.ID_user', 'user.ID_user')
                        ->where('contestant_list.ID_competition', $request->ID_competition)
                        ->where('contestant_list.attendance', 1)
                        ->where('games.ID_type', 3)
                        ->select('contestant_list.*', 'user.full_name')
                        ->distinct()
                        ->get();
        $gamesForFinal = Games::where('ID_competition', $request->ID_competition)
                        ->where('ID_type', 3)
                        ->orderBy('ID_games')
                        ->get();

                        $ID_competition = $request->ID_competition; // Replace with your desired competition ID

        $query = "SELECT 
                        SUM(score) as summed_score, 
                        GROUP_CONCAT(score) as score, 
                        ID_contestant, 
                        full_name 
                    FROM 
                        (
                        SELECT 
                            c.ID_contestant, 
                            d.full_name, 
                            sub_table.score 
                        FROM 
                            (
                            SELECT 
                                ID_contestant, 
                                score, 
                                ID_judge, 
                                @rn := CASE WHEN @ID_contestant = ID_contestant THEN @rn + 1 ELSE 1 END AS rn, 
                                @ID_contestant := ID_contestant 
                            FROM 
                                (
                                    SELECT 
                                        @ID_contestant := NULL, 
                                        @rn := NULL
                                ) vars, 
                                (
                                SELECT 
                                    * 
                                FROM 
                                    score_list 
                                WHERE 
                                    ID_contestant IN (
                                    SELECT 
                                        ID_contestant 
                                    FROM 
                                        score_list
                                    ) 
                                    AND ID_judge = 0 
                                    AND ID_games IN (
                                    SELECT 
                                        ID_games 
                                    FROM 
                                        games 
                                    WHERE 
                                        ID_type IN (
                                        SELECT 
                                            ID_type 
                                        FROM 
                                            competition_type 
                                        WHERE 
                                            name = 'Final'
                                        ) 
                                        AND ID_competition = ?
                                    ) 
                                ORDER BY 
                                    ID_contestant, 
                                    score DESC
                                ) a
                            ) as sub_table 
                            INNER JOIN contestant_list c on c.ID_contestant = sub_table.ID_contestant 
                            INNER JOIN user d ON c.ID_user = d.ID_user 
                            INNER JOIN competition_list e ON c.ID_competition = e.ID_competition 
                        WHERE 
                            rn <= e.final 
                            AND c.attendance = '1' 
                        ORDER BY 
                            ID_contestant, 
                            score DESC
                        ) as dd 
                    GROUP BY 
                        ID_contestant,
                        full_name 
                    ORDER BY 
                        summed_score DESC 
                    LIMIT 
                        0, 10
                    ";
                        
        $summedScores = DB::select($query, [$ID_competition]);
        foreach ($summedScores as $contestant) {
            $scoresArray = explode(',', $contestant->score);
            rsort($scoresArray, SORT_NUMERIC);
            $contestant->sorted_scores = $scoresArray;
        }
        
        // Step 2: Calculate final score based on top N scores
        foreach ($summedScores as $contestant) {
            $topNScores = array_slice($contestant->sorted_scores, 0, $competition->final);
            $contestant->final_score = number_format(array_sum($topNScores), 2);
        }
        
        // Step 3: Sort contestants based on final scores
        usort($summedScores, function ($a, $b) {
            return $b->final_score <=> $a->final_score; // Sort in descending order
        });
        
        // Limit to top 10 contestants (or another number if you wish)
        $topContestants = array_slice($summedScores, 0, 10);
        
        // Get the contestants with their respective scores
        $scoresByFinal = [];
        foreach ($contestants as $contestant) {
            foreach($gamesForFinal->pluck('ID_games') as $game) {
                // $selectSumBestFourScore = Score::where('ID_games', $game)
                //             ->where('ID_contestant', $contestant->ID_contestant)
                //             ->where('fixed', 0)
                //             ->where('ID_judge', '0')
                //             ->select('score')
                //             ->limit(4)
                //             ->orderByDesc('score')
                //             ->get();
                // return dd($selectSumBestFourScore, $game, $contestant->ID_contestant);
                $scores = Score::where('ID_games', $game)
                            ->where('ID_contestant', $contestant->ID_contestant)
                            ->where('fixed', 0)
                            ->where('ID_judge', '0')
                            // create subquery to get best 4 of scores summed
                            ->select('score')
                            ->orderBy('ID_games')
                            ->first();
                $scoresByFinal[$contestant->ID_contestant][$game] = [ 
                    'score' => $scores->score ?? 0,
                    'highlights' => false,
                ];
            }
        }

        // Calculate the sum of all scores for each contestant in each final
        // $sumOfTopScores = [];
        // foreach ($contestants as $contestant) {
        //     $scoresInFinals = [];
        //     for ($i = 1; $i <= $competition->final; $i++) {
        //         $scores = $contestant->scores->where('final', $i);
        //         $sumOfScores = $scores->sum('score');
        //         $scoresInFinals["F$i"] = $sumOfScores;
        //     }
        //     $sumOfTopScores[$contestant->ID_contestant] = $scoresInFinals;
        // }

        // $scoresByFinal = [];
        // foreach ($competition->gamesForFinals as $game) {
        //     $scores = Score::whereIn('ID_contestant', $contestants->pluck('ID_contestant'))
        //         ->where('ID_games', $game->ID_games)
        //         ->where('fixed', 0)
        //         ->get();

        //     // Store the scores in an array, grouped by the contestant ID
        //     foreach ($scores as $score) {
        //         $scoresByFinal[$game->ID_games][$score->ID_contestant][] = $score->score;
        //     }
        // }

        // return dd($scoresByFinal);

        // Fetch the contestant names from the User table using a separate query
        $contestantIds = $contestants->pluck('ID_user')->toArray();
        $contestantNames = DB::table('user')->whereIn('ID_user', $contestantIds)->pluck('full_name', 'ID_user');

        $data = [
            'competition' => $competition,
            // 'contestants' => $contestants,
            // 'contestantNames' => $contestantNames,
            // 'scoresByFinal' => $scoresByFinal
            'summedScores' => $topContestants,
        ];

        return response()->json([
            'status' => 'success',
            'data' => $data,
            'code' => 200
        ], 200);
    }
}
