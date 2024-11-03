<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user) {
            if ($user->role_id === 1 && $request->is('admin/*')) {
                return redirect('/');
            } elseif ($user->role_id === 2 && $request->is('admin/*')) {
                return redirect('/admin/dashboard');
            }
        } else {
            return redirect('/login');
        }

        return $next($request);
    }
}
