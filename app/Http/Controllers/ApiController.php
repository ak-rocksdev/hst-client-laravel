<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Competition;
use App\Models\User;
use App\Models\Event;
use App\Models\Contestant;
use App\Models\Games;
use App\Models\Score;
use App\Models\UserOrigin;
use App\Models\Participant;
use App\Models\Judge;

use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

use DB;

use Auth;

use App\Http\Requests\CreateContestantRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Requests\UserPhotoProfileUpdateRequest;
use App\Http\Requests\UserPasswordUpdateRequest;
use App\Http\Requests\VerifyContestantScoreRequest;

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

    // API to fix Database (Used for setup only)
    public function updateUserImages() {
        // Retrieve all users
        $users = User::all();

        foreach ($users as $user) {
            // Construct the file path using the user's email
            $filePath = storage_path('app/public/user/' . $user->email . '.jpg');

            // Check if the file exists
            if (File::exists($filePath)) {
                // Update the photoFile column for the user
                $user->photoFile = $user->email . '.jpg';
                $user->save();
            }
        }
        
        return "Users updated!";
    }

    public function updateUserById(UserUpdateRequest $request) {
        $data = $request->validated();

        $user = User::where('ID_user', $data['user']['ID_user'])->first();
        if($user->locale != $data['user']['locale']) {
            session(['lang' => $data['user']['locale']]);
        }

        // check if any data change from the previous data use isDirty()
        $user->full_name = $data['user']['full_name'];
        $user->nick_name = $data['user']['nick_name'];
        $user->email = $data['user']['email'];
        $user->dateofbirth = $data['user']['dateofbirth'];
        $user->stance = $data['user']['stance'];
        $user->country_code = $data['user']['country_code'];
        $user->phone = $data['user']['phone'];
        $user->country_code_id = $data['user']['country_code_id'];
        $user->instagram = $data['user']['instagram'];
        
        $userDirty = $user->isDirty();

        if($userDirty) {
            $user->update($data['user']);
        }

        $user->update($data['user']);

        $userOrigin = UserOrigin::where('user_id', $data['user_origin']['user_id'])->first();

        if($userOrigin) {
            $userOrigin->user_id = $data['user_origin']['user_id'];
            $userOrigin->country_id = $data['user_origin']['country_id'];
            $userOrigin->country_name = $data['user_origin']['country_name'];
            $userOrigin->state_id = $data['user_origin']['state_id'];
            $userOrigin->state_name = $data['user_origin']['state_name'];
            $userOrigin->city_id = $data['user_origin']['city_id'];
            $userOrigin->city_name = $data['user_origin']['city_name'];
            $userOrigin->indo_province_id = $data['user_origin']['indo_province_id'];
            $userOrigin->indo_province_name = $data['user_origin']['indo_province_name'];
            $userOrigin->indo_city_id = $data['user_origin']['indo_city_id'];
            $userOrigin->indo_city_name = $data['user_origin']['indo_city_name'];
        }
        
        if($userOrigin == null) {
            UserOrigin::create($data['user_origin']);
        } else {
            if($userOrigin->isDirty()) {
                $userOrigin->update($data['user_origin']);
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => $user,
            'code' => 200,
            'redirect' => $userDirty ? '/profile/edit' : null
        ], 200);
    }

    public function getUserOriginByUserId($userId) {
        $userOrigin = UserOrigin::where('user_id', $userId)->first();
        return response()->json([
            'status' => 'success',
            'data' => $userOrigin,
            'code' => 200
        ], 200);
    }

    public function getCountryCodeDetail($countryCode) {
        $countryCodeDetail = DB::table('country_code')->where('country_code', $countryCode)->first();
        return response()->json([
            'status' => 'success',
            'data' => $countryCodeDetail,
            'code' => 200
        ], 200);
    }

    //create file image upload for user photo profile, and write the file name to the database
    public function uploadPhotoProfileByUserId(UserPhotoProfileUpdateRequest $request)
    {
        // Get the uploaded file
        $file = $request->file('file');

        // Define the upload directory to storage/user and file name
        $uploadDir = 'storage/user/';
        $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
        $uploadPath = $uploadDir . $fileName;

        // return dd($uploadPath, $fileName, $file, $uploadDir);

        // Store the file in the storage directory
        // Storage::putFileAs($uploadDir, $file, $fileName);

        $image = Image::make($file);
        $image->resize(1000, 1000, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $image->save($uploadPath);

        // if the file already exist, delete the previous file
        $user = auth()->user();
        if($user->photoFile != null) {
            $filePath = storage_path('app/public/user/' . $user->photoFile);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        }

        // Insert the file name into the database
        $user = auth()->user();
        $user->photoFile = $fileName;
        $user->save();

        // Return a success response
        return response()->json([
            'messages' => ['Profile photo uploaded successfully.'],
            'redirect' => '/profile/edit',
        ]);
    }

    // API to fill the participant table, after create the participant table on the database
    public function fillParticipantTable()
    {
        $users = User::all();

        foreach($users as $user) {
            $competitions = Competition::all();
            foreach($competitions as $competition) {
                $contestant = Contestant::where('ID_user', $user->ID_user)
                                            ->where('ID_competition', $competition->ID_competition)
                                            ->where('attendance', 1)
                                            ->first();
                if($contestant != null) {
                    Participant::create([
                        'ID_user' => $user->ID_user,
                        'ID_competition' => $competition->ID_competition,
                        'created_at' => $contestant->insert_date,
                        'created_by' => $user->ID_user,
                        'updated_at' => now(),
                        'updated_by' => $user->ID_user
                    ]);
                }

                $judge = Judge::where('ID_user', $user->ID_user)
                                            ->where('ID_competition', $competition->ID_competition)
                                            ->first();

                if($judge != null) {
                    Participant::create([
                        'ID_user' => $judge->ID_user,
                        'ID_competition' => $competition->ID_competition,
                        'created_at' => now(),
                        'created_by' => $judge->ID_user,
                        'updated_at' => now(),
                        'updated_by' => $judge->ID_user
                    ]);
                }
            }
        }

        return response()->json([
            'status' => 'success',
            'messages' => 'OK',
            'code' => 200
        ], 200);
    }

    public function getGamesByCompetitionIdAndType(Request $request) {
        $id_competition = $request->id_competition;
        $id_type = $request->id_type;

        $games = Games::where('ID_competition', $id_competition)
                        ->where('ID_type', $id_type)
                        ->orderBy('ID_games')
                        ->distinct()
                        ->select('ID_type', 'ID_games')
                        ->get();

        return response()->json([
            'status' => 'success',
            'data' => $games,
            'code' => 200
        ], 200);
    }

    public function getRoundByCompetitionId(Request $request) {
        $ID_competition = $request->id_competition;
        $games = Games::where('ID_competition', $ID_competition)->orderBy('ID_type')->distinct()->select('ID_type')->get();

        return response()->json([
            'status' => 'success',
            'data' => $games,
            'code' => 200
        ], 200);
    }
    
    public function getParticipantsByCompetitionId(Request $request) {
        $id_type = $request->id_type;
        $id_competition = $request->id_competition;
        $id_games = $request->id_games;

        $participants = Participant::leftJoin('contestant_list', 'participant.ID_user', 'contestant_list.ID_user')
                        ->leftJoin('competition_list', 'contestant_list.ID_competition', 'competition_list.ID_competition')
                        ->leftJoin('user', 'contestant_list.ID_user', 'user.ID_user')
                        ->leftJoin('user_origin', 'user.ID_user', 'user_origin.user_id')
                        ->leftJoin('event_list', 'competition_list.ID_event', '=', 'event_list.ID_event')
                        ->leftJoin('running_list', 'running_list.ID_contestant', 'contestant_list.ID_contestant')
                        ->leftJoin('games', 'running_list.ID_games', 'games.ID_games')
                        ->where('competition_list.ID_competition', $id_competition)
                        ->where('running_list.ID_games', $id_games)
                        ->where('contestant_list.attendance', 1)
                        ->where('games.ID_type', $id_type)
                        ->select(
                            'event_list.name as event_name',
                            'competition_list.level as competition_name',
                            'games.ID_type as games_type',
                            'running_list.ID_games',
                            'running_list.increment',
                            'running_list.groups',
                            'running_list.status',
                            'running_list.ID_running',
                            'contestant_list.ID_competition',
                            'contestant_list.ID_contestant',
                            'contestant_list.ID_user',
                            'user.full_name',
                            'user.dateofbirth',
                            DB::raw('FLOOR(DATEDIFF(CURRENT_DATE, user.dateofbirth) / 365) as age'),
                            'user.email',
                            'user.photoFile',
                            'user.stance',
                            'user_origin.country_id',
                            'user_origin.country_name',
                            'user_origin.state_name',
                            'user_origin.city_name',
                            'user_origin.indo_province_name',
                            'user_origin.indo_city_name'
                        )
                        ->distinct()
                        ->orderBy('running_list.increment')
                        ->get();  // perlu di filter berdasarkan qual / final dan games

        return response()->json([
            'status' => 'success',
            'data' => $participants,
            'code' => 200
        ], 200);
    }

    public function updatePasswordByUserId(UserPasswordUpdateRequest $request) {
        $validated = $request->validated();

        $user = auth()->user();
        $user = User::where('ID_user', $user->ID_user)->first();
        $user->new_password = $validated['new_password'];
        $user->save();

        return response()->json([
            'status' => 'success',
            'messages' => [
                '0' => __('messages.response_new_password_created')
            ],
            'code' => 200
        ], 200);
    }

    public function getContestantScoreByJudgeIdOnCurrentGames(Request $request) {
        $score = Score::where('ID_contestant', $request->ID_contestant)
                        ->where('ID_games', $request->ID_games)
                        ->where('ID_judge', $request->ID_judge)
                        ->first();

        return response()->json([
            'status' => 'success',
            'data' => $score,
            'code' => 200
        ], 200);
    }

    public function setContestantScoreByJudgeIdOnCurrentGames(Request $request){
        // check if score where ID_contestant, ID_games, ID_judge = 0 is exist
        $score = Score::where('ID_contestant', $request->ID_contestant)
                        ->where('ID_games', $request->ID_games)
                        ->where('ID_judge', 0)
                        ->first();

        if($score == null) {
            $score = Score::where('ID_contestant', $request->ID_contestant)
                            ->where('ID_games', $request->ID_games)
                            ->where('ID_judge', $request->ID_judge)
                            ->first();
            if($score == null) {
                Score::create([
                    'ID_contestant' => $request->ID_contestant,
                    'ID_games' => $request->ID_games,
                    'ID_judge' => $request->ID_judge,
                    'score' => $request->score,
                    'fixed' => 1
                ]);
            } else {
                $score->score = $request->score;
                $score->fixed = 1;
                $score->save();
            }

            return response()->json([
                'status' => 'success',
                'messages' => [
                    '0' => __('messages.response_score_is_set')
                ],
                'code' => 200
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'messages' => [
                    '0' => 'Score is Already Submitted by The Head Judge'
                ],
                'code' => 400
            ], 400);
        }
    }

    public function verifyScoreByContestantId(VerifyContestantScoreRequest $request) {
        // validate if score from all judges is already set - formRequest
        // validate if verified score is already exist - formRequest
        // calculate the average score from DB selected system
        // create database record with ID_judge = 0, score from the calculation, fixed value = 1, and current ID_contestant
        // return response success

        return response()->json([
            'status' => 'success',
            'messages' => [
                '0' => __('messages.response_score_is_verified')
            ],
            'code' => 200
        ], 200);
    }

    private function calculateAverageScore($scores) {
        $sum = 0;
        foreach($scores as $score) {
            $sum += $score;
        }
        return $sum / count($scores);
    }
}
