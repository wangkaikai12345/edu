<?php

namespace App\Http\Controllers\Front;

use App\Enums\SettingType;
use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Front\RegisterRequest;
use App\Models\User;
use Facades\App\Models\Setting;
use App\Notifications\WelcomeNotification;
use Cache;

class RegisterController extends Controller
{
    /**
     * @var array 注册配置
     */
    protected $registerConfig = [];

    /**
     * @var string 缓存键
     */
    protected $registerTimesForIp = 'register_times_for_ip:';

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * 注册页面的表单
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author 王凯
     */
    public function showRegistrationForm()
    {
        if ($this->isWx()) {
            return redirect()->to(route('login'));
        }

        return frontend_view('register');
    }

    /**
     * 注册
     *
     * @param RegisterRequest $request
     * @param User $user
     * @return mixed
     * @author 王凯
     */
    public function register(RegisterRequest $request, User $user)
    {
        // 获取验证码，存在即验证，否则错误响应
        if (!($verifyData = \Cache::get($request->verification_key))) {
            return back()->with('danger', '验证码不存在');
        }

        if (!hash_equals((string)$verifyData['code'], (string)$request->verification_code)) {
            return back()->with('danger', __('Verification code is error.'));
        }

        // 检查注册配置
        $this->checkRegisterConfig(key($verifyData));

        \DB::transaction(function () use ($user, $request, $verifyData) {
            // 创建用户信息
            $user->username = $request->username;
            $user->phone = $verifyData['phone'] ?? null;
            $user->email = $verifyData['email'] ?? null;
            $user->password = bcrypt($request->password);
            $user->invitation_code = str_random(6);
            $user->registered_ip = $request->ip();
            // 如果存在邀请人，并记录邀请人
            $request->invitation_code && $user->inviter_id = User::where($request->only('invitation_code'))->value('id');
            $user->save();

            // 创建用户详情
            $user->profile()->create();

            // 自动为其添加学员角色
            $user->assignRole(UserType::STUDENT);
        });

        // 清除验证码缓存
        \Cache::forget($request->verification_key);

        // 检查注册配置是否发送通知
        if ($this->registerConfig && $this->registerConfig['is_sent_welcome_notification']) {
            // 发送欢迎通知
            $user->notify(new WelcomeNotification(['title' => $this->registerConfig['welcome_title'], 'content' => $this->registerConfig['welcome_content']]), true);

        }

        // 检查注册限制ip配置是否存在
        if ($this->registerConfig && $this->registerConfig['register_limit'] && $this->registerConfig['register_expires']) {

            // 注册成功，缓存注册ip
            $cacheKey = $this->registerTimesForIp . $request->ip();
            $registerIpCache = Cache::get($cacheKey);

            if ($registerIpCache) {
                Cache::increment($cacheKey, 1);
            } else {
                Cache::add($cacheKey, 1, $this->registerConfig['register_expires'] ?? 60);
            }
        }

        auth('web')->login($user);

        return ajax('200', '注册成功');
    }

    /**
     *
     * 检测注册系统配置
     */
    protected function checkRegisterConfig($type)
    {
        // 支持手机，邮箱注册
        if (!in_array($type, ['email', 'phone'])) return redirect()->back()->with('error', '注册方式系统不支持');

        // 获取注册配置
        $setting = Setting::where('namespace', SettingType::REGISTER)->value('value');

        if ($setting) {

            // 检查注册模式
            if ($setting['register_mode'] == 'closed') return redirect()->back()->with('error', __('Banned registering.'));

            if ($setting['register_mode'] != 'email_phone' && $setting['register_mode'] != $type) return redirect()->back()->with('error', __('Banned registering.'));

            // 检查注册ip限制
            if ($setting['register_limit'] && $setting['register_expires']) {
                $times = Cache::get($this->registerTimesForIp . request()->ip());
                // 如果缓存存在
                if ($times && $times >= $setting['register_limit']) {
                    return redirect()->back()->with('error', __('The same ip can be registered :times times in :minutes minutes.', ['times' => $setting['register_limit'], 'minutes' => $setting['register_expires']]));
                }
            }

            $this->registerConfig = $setting;
        }
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
