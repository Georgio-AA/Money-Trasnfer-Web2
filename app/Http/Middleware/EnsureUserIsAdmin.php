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

        $authenticatedUser = $request->user();

        if ($authenticatedUser) {
            $isAdmin = (bool) $authenticatedUser->is_admin;
        } elseif ($request->session()->has('user')) {
            $sessionUser = $request->session()->get('user');

            if (is_array($sessionUser) && ! empty($sessionUser['id'])) {
                $user = User::find($sessionUser['id']);

                if ($user) {
                    $isAdmin = (bool) $user->is_admin;
                    auth()->setUser($user);
                }
            }

            if (! $isAdmin && ! empty($sessionUser['is_admin'])) {
                $isAdmin = (bool) $sessionUser['is_admin'];
            }
        }

        if (! $isAdmin) {
            abort(403, 'This action is unauthorized.');
        }

        return $next($request);
    }
}
