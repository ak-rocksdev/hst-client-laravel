<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TeamManagerApplication;
use App\Models\Notification;

use DB;

use App\Http\Requests\TeamApplicationCreateRequest;

class TeamPageController extends Controller
{
    // PAGE VIEW
    public function viewTeamManagePage()
    {
        return view('pages.team.manage');
    }

    // API
    public function createTeamApplication(TeamApplicationCreateRequest $request)
    {
        DB::beginTransaction();

        try{
            $data = $request->validated();
            $teamManagerApplication = TeamManagerApplication::create($data);

            // -- type of notification:
            // -- 1: announcement
            // -- 2: invitation
            // -- 3: reminder

            $notification = Notification::create([
                'ID_user_receiver' => $data['ID_user'],
                'type' => 1,
                'title' => __('messages.notification_title_team_manager_application_sent'),
                'description' => __('messages.response_team_manager_application_created'),
            ]);
            
            DB::commit();
            return response()->json([
                'status' => 'success',
                'messages' => [
                    '0' => __('messages.response_team_manager_application_created')
                ],
                'code' => 200
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'failed',
                'messages' => [
                    '0' => $e->getMessage()
                ],
                'code' => 400
            ], 400);
        }
    }
}
