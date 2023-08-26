<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;

use App\Models\User;
use Auth;
use Carbon\Carbon;

use DB;
use Validator;

class AuthController extends Controller
{
    public function doLogin(UserLoginRequest $request) {
        $findUserByEmail = User::where('email', $request->email)->first();
        $password = md5($request->get('password'));

        $user = [
            'email' => $request->get('email'),
            'password'=> $password
        ];

        if($findUserByEmail && $findUserByEmail->password == $password) {
            $request->session()->put('user-temp', $findUserByEmail->email);
            return response()->json([
                'status' => 'success',
                'message' => __('messages.response_login_success'),
                'code' => 200,
                'redirect' => '/update-password'
            ], 200);
        }

        $user['password'] = $request->get('password');
        $authenticated = Auth::attempt($user);
        if($authenticated) {
            $findUserByEmail->last_login_at = Carbon::now();
            $findUserByEmail->save();

            return response()->json([
                'status' => 'error',
                'messages' => ['success' =>[__('messages.response_login_success')]],
                'code' => 200,
                'redirect' => session()->get('url.intended') ? session()->get('url.intended') : '/'
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'messages' => ['error' =>[__('messages.response_login_failed')]],
                'code' => 400
            ], 400);
        }
    }

    public function doLogout(Request $request) {
        $previousURL = url()->previous();
        if (Auth::guard('web')->check()) {
            Auth::logout();
            $request->session()->flush();
            if($previousURL == request()->getSchemeAndHttpHost().'/profile') {
                return redirect('/');
            }
            return redirect(url()->previous());
        }
        return redirect(url()->previous());
    }

    public function doForceUpdatePassword(Request $request) {
        DB::beginTransaction();

        $validator = Validator::make($request->all(), [
            'password'              => 'required|min:6|confirmed',
            'password_confirmation' => 'required'
        ]);

        if($validator->fails()) {
            DB::rollback();
            $messages = $validator->errors()->all();
            return redirect()->back()->with('errors', $messages);
        }
        $emailSession = session('user-temp');
        $findUserByEmail = User::where('email', $emailSession)->first();
        $password = bcrypt($request->get('password'));

        $findUserByEmail->password = $password;
        $findUserByEmail->password_version = 1;
        $findUserByEmail->save();

        session()->forget('user-temp');

        DB::commit();

        return redirect()->route('login')->with('success', __('messages.response_password_updated'));
    }

    public function doRegister(UserRegisterRequest $request) {
        DB::beginTransaction();

        try {
            $validated = $request->validated();
            $user = User::create($validated);


            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => __('messages.response_registration_success'),
                'code' => 200,
                'redirect' => '/login'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('errors', $e->getMessage());
        }
    }
}
