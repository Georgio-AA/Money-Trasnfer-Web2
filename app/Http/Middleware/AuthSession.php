<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthSession
{
    public function handle(Request $request, Closure $next)
    {
        $publicPaths = ['login', 'signup', '/']; // pages guests can access

        // Redirect logged-in users away from login/signup
        if (in_array($request->path(), $publicPaths)) {
            if (session()->has('user')) {
                return redirect()->route('home');
            }
            return $next($request);
        }

        // Block guests from protected pages
        if (!session()->has('user')) {
            return redirect()->route('signup')->with('error', 'Please create an account or log in to access this page.');
        }

        return $next($request);
    }
}
