<?php

namespace App\Http\Controllers\Front;

use App\Enums\SettingType;
use App\Events\LoginEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\LoginRequest;
use Facades\App\Models\Setting;
use App\Models\User;
use Cache;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;
    /**
     * 基于 IP 设置最大密码错误次数的缓存键
     *
     * @var string
     */
    protected $passwordErrorForIpCacheKey = 'password_error_times_for_ip:';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * 展示登陆表单
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author 王凯
     */
    public function showLoginForm(Request $request)
    {
        $request->session()->put('redirectPath', \URL::previous());

        // 微信浏览器
        if ($this->isWx()) {

            // 实例化
            $app = \EasyWeChat::officialAccount();

            // 获取授权服务
            $oauth = $app->oauth;

            // 跳转授权
            return $oauth->redirect();
        }

        return frontend_view('login');
    }

    /**
     * 登陆
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @author 王凯
     */
    public function login(LoginRequest $request)
    {
        // 查询登录配置
        $setting = Setting::namespace(SettingType::LOGIN);

        // 网站配置登录限制
        $loginMode = $setting['login_mode'] ?? 'all';
        $isLimitIp = $setting['is_limit_ip'] ?? false;
        $isLimitUser = $setting['is_limit_user'] ?? true;
        $passwordErrorTimesForUser = $setting['password_error_times_for_user'] ?? config('setting.default.login.password_error_times_for_user');
        $passwordErrorTimesForIp = $setting['password_error_times_for_ip'] ?? config('setting.default.login.password_error_times_for_ip');
        $expires = $setting['expires'] ?? config('setting.default.login.expires');
        $onlyAllowVerifiedEmailToLogin = $setting['only_allow_verified_email_to_login'] ?? false;

        // 判断网站配置允许登陆
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
                return back()->with('danger', __('Banned logging.'));
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

        // 判断用户锁定
        if ($user->locked) return back()->with('danger', '用户被禁用');

        // 判断邮箱是否验证允许登陆
        if ($onlyAllowVerifiedEmailToLogin && !$user->is_email_verified) {
            return back()->with('danger', __('Unverified email cannot login.'));
        }

        // IP 角度限制登陆：
        $cacheKey = $this->passwordErrorForIpCacheKey . $request->ip();
        $lastPasswordError = Cache::get($cacheKey);

        if ($isLimitIp && $lastPasswordError && $lastPasswordError >= $passwordErrorTimesForIp) {
            // TODO 这里若更换为其它的缓存驱动可能会产生问题
            $ttl = \Redis::ttl(Cache::getPrefix() . $cacheKey);
            return back()->with('danger', __('Error times too many, Account is be locked until :time', ['time' => now()->addSeconds($ttl)]));
        }

        // 用户角度：判断用户最后一次错误时间是否于 x 分钟之内，并且错误次数大于 y 次
        if ($isLimitUser && $user->locked_deadline && $user->locked_deadline->gt(now())) {
            return back()->with('danger', __('Error times too many, Account is be locked until :time', ['time' => $user->locked_deadline]));
        }

        // 验证密码
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
            default:
                $credentials = [];
                break;
        }

        // 认证登陆
        $token = Auth::guard('web')->attempt($credentials);

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

            return back()->with('danger', $message);
        }

        // 清空错误信息
        $user->last_password_failed_at = null;
        $user->locked_deadline = null;
        $user->password_error_times = 0;
        $user->save();
        Cache::delete($cacheKey);

        // 触发登录成功事件
        event(new LoginEvent($user, now(), $request->ip()));

        $url = $request->session()->get('redirectPath');

        $request->session()->forget('redirectPath');

        return redirect($url)->with('success', '亲爱的' . $user->username . '  欢迎回来！');
    }

    /**
     * 退出登陆
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @author 王凯
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        return redirect('/');
    }


    /**
     * 判断是否微信浏览器
     *
     * @return bool
     */
    private function isWx()
    {
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {

            return true;

        }

        return false;
    }
}
