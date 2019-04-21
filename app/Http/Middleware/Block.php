<?php

namespace App\Http\Middleware;

use Closure;

/**
 * 检查用户是否被禁用
 *
 * @package App\Http\Middleware
 */
class Block
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check() && auth()->user()->locked) {
            abort(423, '用户被禁用！');
        }

        return $next($request);
    }
}
