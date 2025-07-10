<?php

namespace App\Http\Middleware;

use Closure;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckSuspended
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->isSuspended()) {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'email' => 'Your account has been suspended.',
            ]);
        }

        return $next($request);
    }
}
