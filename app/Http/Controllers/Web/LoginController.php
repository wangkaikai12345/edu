<?php

namespace App\Http\Controllers\Web;

use App\Enums\SettingType;
use App\Events\LoginEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\LoginRequest;
use App\Traits\ResponseWithToken;
use App\Http\Requests\Web\SmsLoginRequest;
use App\Http\Transformers\UserTransformer;
use Facades\App\Models\Setting;
use App\Models\User;
use Cache;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class LoginController extends Controller
{

    use ResponseWithToken;

    /**
     * 基于 IP 设置最大密码错误次数的缓存键
     *
     * @var string
     */
    protected $passwordErrorForIpCacheKey = 'password_error_times_for_ip:';

    /**
     * @SWG\Response(response="TokenResponse",description="",@SWG\Schema(
     *  @SWG\Property(property="access_token",example="2HbYJv4wsCNRAHzEsk6t4zly/kBcLMg4v8iTgawpURA="),
     *  @SWG\Property(property="token_type",example="Bearer"),
     *  @SWG\Property(property="expires_in",description="单位秒",example="3600"),
     * ))
     */

    /**
     * @SWG\Post(
     *  path="/login",
     *  tags={"web/auth"},
     *  summary="登录",
     *  description="",
     *  operationId="auth/login",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="username",in="formData",description="允许手机、账户、邮箱登录",required=true,type="string",default="admin"),
     *  @SWG\Parameter(name="password",in="formData",description="",required=true,type="string",minLength=6,default="123456"),
     *  @SWG\Response(response=200,ref="#/responses/TokenResponse"),
     *  @SWG\Response(response=403,ref="#responses/UnauthorizedException"),
     *  @SWG\Response(response=422,ref="#/responses/ResourceException"),
     * )
     */
    public function store(LoginRequest $request)
    {
        // 查询登录配置
        $setting = Setting::namespace(SettingType::LOGIN);

        // 登录限制
        $loginMode = $setting['login_mode'] ?? 'all';
        $isLimitIp = $setting['is_limit_ip'] ?? false;
        $isLimitUser = $setting['is_limit_user'] ?? true;
        $passwordErrorTimesForUser = $setting['password_error_times_for_user'] ?? 5;
        $passwordErrorTimesForIp = $setting['password_error_times_for_ip'] ?? 5;
        $expires = $setting['expires'] ?? 60;
        $onlyAllowVerifiedEmailToLogin = $setting['only_allow_verified_email_to_login'] ?? false;

        switch ($loginMode) {
            case 'phone':
                $type = 'phone';
                $user = User::where('phone', $request->username)->first();
                break;
            case 'email':
                $type = 'email';
                $user = User::where('email', $request->username)->first();
                break;
            case 'closed':
                $this->response->errorForbidden(__('Banned logging.'));
                break;
            case 'all':
            default:
                // 查询用户
                if (filter_var($request->username, FILTER_VALIDATE_EMAIL)) {
                    $type = 'email';
                    $user = User::where('email', $request->username)->first();
                } else if (preg_match('/^1[3-9]\d{9}$/', $request->username)) {
                    $type = 'phone';
                    $user = User::where('phone', $request->username)->first();
                } else {
                    $type = 'username';
                    $user = User::where('username', $request->username)->first();
                }
                break;
        }

        if ($user->locked) {
            abort('423', __('User is disabled.'));
        }

        if ($onlyAllowVerifiedEmailToLogin && !$user->is_email_verified) {
            $this->response->errorForbidden(__('Unverified email cannot login.'));
        }

        // IP 角度：
        $cacheKey = $this->passwordErrorForIpCacheKey . $request->ip();
        $lastPasswordError = Cache::get($cacheKey);

        if ($isLimitIp && $lastPasswordError && $lastPasswordError >= $passwordErrorTimesForIp) {
            // TODO 这里若更换为其它的缓存驱动可能会产生问题
            $ttl = \Redis::ttl(Cache::getPrefix() . $cacheKey);
            $this->response->errorForbidden(__('Error times too many, Account is be locked until :time', ['time' => now()->addSeconds($ttl)]));
        }

        // 用户角度：判断用户最后一次错误时间是否于 x 分钟之内，并且错误次数大于 y 次
        if ($isLimitUser && $user->locked_deadline && $user->locked_deadline->gt(now())) {
            $this->response->errorForbidden(__('Error times too many, Account is be locked until :time', ['time' => $user->locked_deadline]));
        }

        // 获取 token
        switch ($type) {
            case 'email':
                $credentials = ['email' => $request->username, 'password' => $request->password];
                break;
            case 'phone':
                $credentials = ['phone' => $request->username, 'password' => $request->password];
                break;
            case 'username':
                $credentials = ['username' => $request->username, 'password' => $request->password];
                break;
        }

        $token = auth()->attempt($credentials);

        // 限制IP登陆错误次数
        if (!$token && $isLimitIp) {
            $lastPasswordError ? Cache::increment($cacheKey, 1) : Cache::add($cacheKey, 1, $expires);
            $remainingTimes = ($passwordErrorTimesForIp - Cache::get($cacheKey)) >= 0 ? $passwordErrorTimesForIp - Cache::get($cacheKey) : 0;
        }

        // 限制用户登录错误次数
        if (!$token && $isLimitUser) {
            // 用户查询：判断上次错误时间是否超过 x 分钟，若超过，则初始化错误次数，否则递增 1。
            if ($user->last_password_failed_at && $user->last_password_failed_at->diffInMinutes(now()) > $expires) {
                $user->password_error_times = 1;
            } else {
                $user->password_error_times = $user->password_error_times + 1;
            }
            // 若错误次数超过限制次数，那么锁定账户
            if ($user->password_error_times >= $passwordErrorTimesForUser) {
                $user->locked_deadline = now()->addMinutes($expires);
            }
            $user->last_password_failed_at = now();
            $user->save();
            $remainingTimes = ($passwordErrorTimesForUser - $user->password_error_times) >= 0 ? $passwordErrorTimesForUser - $user->password_error_times : 0;
        }

        if (!$token) {
            $message = isset($remainingTimes) ? __('Account or password error, remaining :times', ['times' => $remainingTimes]) : __('Account or password error.');
            $this->response->errorBadRequest($message);
        }
        // 清空错误信息
        $user->last_password_failed_at = null;
        $user->locked_deadline = null;
        $user->password_error_times = 0;
        $user->save();
        Cache::delete($cacheKey);

        // 触发登录成功事件
        event(new LoginEvent($user, now(), $request->ip()));

        return $this->respondWithToken($token);
    }

    /**
     * @SWG\Post(
     *  path="/sms-login",
     *  tags={"web/auth"},
     *  summary="手机短信登录",
     *  description="通过填写手机号发送短信，然后进行登录",
     *  operationId="auth/login",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="sms_key",in="formData",description="短信 Key",required=true,type="string"),
     *  @SWG\Parameter(name="sms_code",in="formData",description="短信验证码",required=true,type="string",minLength=6),
     *  @SWG\Response(response=200,ref="#/responses/TokenResponse"),
     *  @SWG\Response(response=422,ref="#/responses/ResourceException"),
     * )
     */
    public function sms(SmsLoginRequest $request)
    {
        // 查询登录配置
        $setting = Setting::namespace(SettingType::LOGIN);

        // 登录限制
        $loginMode = $setting['login_mode'] ?? 'all';
        if ($loginMode == 'closed') {
            $this->response->errorBadRequest(__('Banned logging.'));
        }

        if (!($cacheData = Cache::get($request->sms_key))) {
            $this->response->errorBadRequest(__('Verification code has been expired.'));
        }

        $user = data_get($cacheData, 'phone') ? User::where('phone', $cacheData['phone'])->first() : null;
        if (!$user) {
            $this->response->errorBadRequest(__('Invalid user.'));
        }

        if ($user->locked) {
            abort('423', __('User is disabled.'));
        }

        $token = JWTAuth::fromUser($user);

        // 触发登录成功事件
        event(new LoginEvent($user, now(), $request->ip()));

        return $this->respondWithToken($token);
    }

    /**
     * @SWG\Get(
     *  path="/me",
     *  tags={"web/auth"},
     *  summary="个人信息",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Response(response=200,description="ok",@SWG\Schema(ref="#/definitions/User")),
     *  @SWG\Response(response=401,ref="#/responses/UnauthorizedException"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function me()
    {
        $user = auth()->user();

        return $this->response->item($user, new UserTransformer());
    }

    /**
     * @SWG\Delete(
     *  path="/logout",
     *  tags={"web/auth"},
     *  summary="退出登录",
     *  description="",
     *  @SWG\Response(response=204,description="ok"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function logout()
    {
        auth()->logout();

        return $this->response->noContent();
    }

    /**
     * @SWG\Patch(
     *  path="/refresh",
     *  tags={"web/auth"},
     *  summary="刷新密钥",
     *  description="在更 expires_in 内的 token 允许请求该接口以换取新的有效的 access_token",
     *  @SWG\Response(response=200,ref="#/responses/TokenResponse"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function refresh()
    {
        try {
            $token = auth()->refresh();
        } catch (TokenExpiredException $e) {
            abort(401);
        } catch (JWTException $e) {
            abort(401);
        }
        return $this->respondWithToken($token);
    }
}
