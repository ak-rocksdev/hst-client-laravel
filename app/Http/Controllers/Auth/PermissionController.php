<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use App\Models\User;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->createRoles();
    }

    private function createRoles()
    {
        $roles = ['super_admin', 'admin', 'event_manager', 'team_manager', 'judge', 'contestant', 'member'];

        foreach ($roles as $role) {
            if (!Role::where('name', $role)->exists()) {
                Role::create(['name' => $role]);
            }
        }
    }

    public function assignRolesToAllUsers()
    {
        // $users = User::all();

        // foreach ($users as $user) {
        //     if ($user->flag == 1) {
        //         $user->assignRole('member');
        //     } elseif ($user->flag == 2) {
        //         $user->assignRole('judge');
        //     } elseif ($user->flag == 3) {
        //         $user->assignRole('super_admin');
        //     }
        // }

        // if current user login is member, return dd role


        return response()->json([
            'status' => 'success',
            'message' => 'Role assigned successfully',
        ]);
    }

}
