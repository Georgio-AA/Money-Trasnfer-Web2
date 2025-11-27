<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthSession
{
    public function handle(Request $request, Closure $next)
    {
    $guestPatterns = ['login', 'signup'];
    $path = $request->path();
    $isRootRequest = $request->is('/') || $path === '' || $path === '/';
        $isGuestRoute = $isRootRequest || $request->is($guestPatterns);

        if (session()->has('user')) {
            if ($isGuestRoute) {
                return redirect()->route('home');
            }

            return $next($request);
        }

        if (! $isGuestRoute) {
            return redirect()->route('signup')->with('error', 'Please create an account or log in to access this page.');
        }

        return $next($request);
    }
}
