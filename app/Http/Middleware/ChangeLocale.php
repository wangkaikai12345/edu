<?php

namespace App\Http\Middleware;

use Closure;

class ChangeLocale
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
        $language = strtolower($request->header('accept-language'));
        switch ($language) {
            case 'en':
                \App::setLocale('en');
                break;
            case 'zh-cn':
            default:
                \App::setLocale('zh-CN');
                break;
        }

        return $next($request);
    }
}
