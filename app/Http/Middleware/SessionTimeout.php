<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Config;

class SessionTimeout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
if (Auth::check()) {
            if (Session::get('last_activity') && (time() - Session::get('last_activity') > Config::get('session.lifetime') * 60)) {
                Auth::logout();
                Session::flush();
                return Redirect::to('/login');
            } else {
                Session::put('last_activity', time());
                return $next($request);
            }
        }
        return $next($request);


        
        
    }
}
