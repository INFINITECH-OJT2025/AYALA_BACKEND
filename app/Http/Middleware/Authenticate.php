<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return response()->json(["error" => "Unauthorized"], 401);
        }

        return $next($request);
    }

    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return response()->json(["error" => "Unauthorized"], 401);
        }
    }
}
