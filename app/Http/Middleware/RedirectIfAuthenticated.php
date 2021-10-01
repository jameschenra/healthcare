<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {

            $redirectRoute = 'user.profile.index';
            if ($guard == 'admins') {
                $redirectRoute = 'admin.home';
            }
            return redirect()->route($redirectRoute);
        }

        return $next($request);
    }
}
