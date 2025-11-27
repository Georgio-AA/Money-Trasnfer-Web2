<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $isAdmin = false;

        // Check if user is authenticated via Laravel auth
        $authenticatedUser = $request->user();
        if ($authenticatedUser && isset($authenticatedUser->role)) {
            $isAdmin = $authenticatedUser->role === 'admin';
        }

        // Check custom session-based auth
        if (!$isAdmin && $request->session()->has('user')) {
            $sessionUser = $request->session()->get('user');

            // Check session role directly
            if (!empty($sessionUser['role']) && $sessionUser['role'] === 'admin') {
                $isAdmin = true;
            }
            // Fallback: check database
            elseif (!empty($sessionUser['id'])) {
                $user = User::find($sessionUser['id']);
                if ($user && $user->role === 'admin') {
                    $isAdmin = true;
                }
            }
        }

        if (!$isAdmin) {
            abort(403, 'This action is unauthorized.');
        }

        return $next($request);
    }
}
