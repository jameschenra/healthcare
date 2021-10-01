<?php

namespace App\Http\Middleware;

use Closure;

class AuthPrimaryMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = auth()->user();
        if ($user && $user->relationship == 'Primary') {
            return $next($request);
        } else {
            abort(403, 'Access denied');
        }
    }
}
