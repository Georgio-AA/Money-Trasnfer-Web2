<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsAgent
{
    public function handle(Request $request, Closure $next)
    {
        // Check if user is in session AND has role "agent"
        if (session()->has('user') && session('user.role') === 'agent') {
            return $next($request);
        }

        return redirect()->route('login')
            ->with('error', 'You must be an agent to access this section.');
    }
}
