<?php

namespace App\Http\Middleware;

use Closure;

class BackstageMiddleware
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

        if (!\Auth::guard('web')->check()) {
            return redirect('login');
        }

        $roles = ['super-admin', 'admin'];

        if (! auth('web')->user()->hasAnyRole($roles)) {

            return redirect('login');
        }

        return $next($request);
    }
}
