<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;

class IsUpdatedPassword
{
    /**
     * 当用户更改过一次密码以后，那么之前的所有 token 失效；
     *  实现：为 jwt token 添加自定义的 key 用来标识用户是否修改密码
     *  key 的生成规则：用户的 password 再进行一次 hash 加密。
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $me = JWTAuth::user();

        $requestKey = JWTAuth::setToken($request->bearerToken())->getClaim('key');

        if (!$requestKey) {
            JWTAuth::invalidate();
            abort(401, '登录授权失效');
        }

        $key = $me->makePasswordTag();
        if ($requestKey !== $key) {
            JWTAuth::invalidate();
            abort(401, '登录授权失效');
        }

        return $next($request);
    }
}
