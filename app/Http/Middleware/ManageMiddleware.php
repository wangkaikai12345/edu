<?php

namespace App\Http\Middleware;

use Closure;

class ManageMiddleware
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

        $roles = ['super-admin', 'admin', 'teacher'];

        if (! auth('web')->user()->hasAnyRole($roles)) {
            abort(404);
        }

        return $next($request);
    }
}
