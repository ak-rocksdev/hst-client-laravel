<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Bus\DispatchesJobs;

use Illuminate\Http\Request;

use Auth;

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
