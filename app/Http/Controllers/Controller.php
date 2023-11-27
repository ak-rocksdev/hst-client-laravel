<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Bus\DispatchesJobs;

use Illuminate\Http\Request;

use Auth;

use App\Models\Notification;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private $user;
    private $signed_in;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();

            view()->share('role', session('role'));
            view()->share('signed_in', $this->user);

            // get notification
            if ($this->user) {
                $notifications = Notification::where('ID_user_receiver', $this->user->ID_user)
                                                ->orWhere('ID_user_receiver', 'all')
                                                ->orderBy('created_at', 'desc')
                                                ->limit(5)
                                                ->get();

                $newNotificationsCount = Notification::where('ID_user_receiver', $this->user->ID_user)
                                                ->where('read_at', null)
                                                ->count();
                view()->share('notifications', $notifications);
                view()->share('newNotificationsCount', $newNotificationsCount);
            }
            

            return $next($request);
        });

    }

    public function setLangId(Request $request){
        if ($request->session()->exists('lang')){
            $request->session()->forget('lang');
            $lang = 'id';
        } else {
            $lang = 'id';
        }
        session(['lang' => $lang]);
        return back();
    }

    public function setLangEn(Request $request){
        if ($request->session()->exists('lang')){
            $request->session()->forget('lang');
            $lang = 'en';
        } else {
            $lang = 'en';
        }
        session(['lang' => $lang]);
        return back();
    }
}
