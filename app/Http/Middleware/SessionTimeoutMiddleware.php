<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SessionTimeoutMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Get the last activity time
        $lastActivity = Session::get('last_activity');

        if ($lastActivity) {
            $sessionTimeout = config('session.lifetime') * 60; // Convert minutes to seconds
            if (time() - $lastActivity > $sessionTimeout) {
                Auth::logout(); // Logout the admin
                Session::flush(); // Clear session
                return response()->json(["error" => "Session expired. Please log in again."], 401);
            }
        }

        // Update last activity timestamp
        Session::put('last_activity', time());

        return $next($request);
    }
}
