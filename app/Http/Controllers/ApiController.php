<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Competition;
use App\Models\User;
use App\Models\Event;
use App\Models\Contestant;

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
}
