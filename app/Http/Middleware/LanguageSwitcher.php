<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use App;
use Config;
use Auth;

class LanguageSwitcher
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $auth = Auth::guard('web')->check();
        if($auth) {
            $user = Auth::user();
            if(isset($user->locale)) {
                $lang = $user->locale;
            } else if ($request->session()->exists('lang')){
                $lang = $request->session()->get('lang');
            } else {
                $lang = Config::get('app.locale');
            }
            App::setLocale($lang);
        } else {
            if ($request->session()->exists('lang')){
                $lang = $request->session()->get('lang');
            } else {
                $lang = Config::get('app.locale');
            }
            App::setLocale($lang);
        }
        App::setLocale(session('lang'));
        return $next($request);
    }
}