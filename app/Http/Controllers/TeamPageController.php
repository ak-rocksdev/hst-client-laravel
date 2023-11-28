<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TeamManagerApplication;
use App\Models\Notification;
use App\Models\UserManager;

use DB;
use Auth;
use App\Models\User;

use App\Http\Requests\TeamApplicationCreateRequest;

class TeamPageController extends Controller
{
    // PAGE VIEW
    public function viewTeamManagePage()
    {
        $user = Auth::user();
        $teams = User::leftJoin('user_manager', 'user_manager.ID_user_member', '=', 'user.ID_user')
                        ->leftJoin('user_origin', 'user_origin.user_id', '=', 'user.ID_user')
                        ->where('user_manager.ID_user_manager', $user->ID_user)
                        ->orderByDesc('user_manager.created_at')
                        ->get();
        return view('pages.team.manage', compact('teams'));
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

    // searchUser
    public function searchUser(Request $request)
    {
        // search everyone except the user itself, the user's team members, and the user's role is admin
        $user = Auth::user();
        $search = $request->search;

        if($search == null || $search == '')
        {
            return response()->json([
                'status' => 'success',
                'code' => 200,
                'data' => []
            ], 200);
        }
        $users = User::leftJoin('user_manager', 'user_manager.ID_user_member', '=', 'user.ID_user')
                        ->leftJoin('user_origin', 'user_origin.user_id', '=', 'user.ID_user')
                        ->leftJoin('model_has_roles', 'model_has_roles.model_id', '=', 'user.ID_user')
                        ->leftJoin('roles', 'roles.id', '=', 'model_has_roles.role_id')
                        ->where('user.ID_user', '!=', $user->ID_user)
                        ->where('roles.name', '!=', 'admin')
                        ->where('roles.name', '!=', 'super_admin')
                        ->where(function ($query) use ($search) {
                            $query->where('user.full_name', 'LIKE', '%' . $search . '%')
                                ->orWhere('user.nick_name', 'LIKE', '%' . $search . '%')
                                ->orWhere('user.email', 'LIKE', '%' . $search . '%')
                                ->orWhere('user.phone', 'LIKE', '%' . $search . '%')
                                ->orWhere('user_origin.country_name', 'LIKE', '%' . $search . '%')
                                ->orWhere('user_origin.city_name', 'LIKE', '%' . $search . '%')
                                ->orWhere('user_origin.indo_city_name', 'LIKE', '%' . $search . '%');
                        })
                        ->limit(10)
                        ->orderBy('user.full_name')
                        ->get();
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'data' => $users
        ], 200);
    }

    public function setMemberByUserId(Request $request)
    {
        DB::beginTransaction();

        try{
            $user = Auth::user();
            $data = $request->all();
            
            $userManager = UserManager::create([
                'ID_user_manager' => $user->ID_user,
                'ID_user_member' => $data['ID_user_member'],
            ]);

            $newMember = User::where('ID_user', $data['ID_user_member'])->first();

            // -- type of notification:
            // -- 1: announcement
            // -- 2: invitation
            // -- 3: reminder

            $this->sendNotification($data['ID_user_member'], 1, 'Successfully Added to Team', 'You have been added to a team by ' . $user->full_name);
            $this->sendNotification($user->ID_user, 1, 'Successfully Added to Team', 'You have successfully added ' . $newMember->full_name . ' to your team');
            
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

    public function sendNotification($ID_user_receiver, $type, $title, $description)
    {
        $notification = Notification::create([
            'ID_user_receiver' => $ID_user_receiver,
            'type' => $type,
            'title' => $title,
            'description' => $description,
        ]);

        return;
    }
}
