<?php

namespace App\Http\Controllers\Web;

use App\Enums\SettingType;
use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Traits\ResponseWithToken;
use App\Http\Requests\Web\RegisterRequest;
use App\Models\User;
use Facades\App\Models\Setting;
use App\Notifications\WelcomeNotification;
use Cache;

class RegisterController extends Controller
{
    use ResponseWithToken;

    /**
     * @var array 注册配置
     */
    protected $registerConfig = [];

    /**
     * @var string 缓存键
     */
    protected $registerTimesForIp = 'register_times_for_ip:';

    /**
     * @SWG\Tag(name="web/register",description="用户注册")
     */

    /**
     * @SWG\Post(
     *  path="/register",
     *  tags={"web/register"},
     *  summary="手机注册",
     *  @SWG\Parameter(name="username",in="formData",type="string",description="用户名，首位字符不能为数字。"),
     *  @SWG\Parameter(name="password",in="formData",type="string",minLength=6,description="密码"),
     *  @SWG\Parameter(name="sms_key",in="formData",type="string",description="短信验证码 Key"),
     *  @SWG\Parameter(name="sms_code",in="formData",type="string",description="短信验证码"),
     *  @SWG\Parameter(name="invitation_code",in="formData",type="string",description="推荐码"),
     *  @SWG\Response(response=201,description="注册成功",ref="#/responses/TokenResponse"))
     * )
     */
    public function store(RegisterRequest $request, User $user)
    {
        // 检查注册配置
        $this->checkRegisterConfig();

        // 检测注册配置手机注册
        if ($this->registerConfig && $this->registerConfig['register_mode'] == 'email') {
            $this->response->errorBadRequest(__('Banned registering.'));
        }

        // 获取验证码，存在即验证，否则错误响应
        if (!($verifyData = \Cache::get($request->sms_key))) {
            $this->response->errorBadRequest(__('Verification code has been expired.'));
        }

        if (!hash_equals((string)$verifyData['code'], (string)$request->sms_code)) {
            $this->response->errorBadRequest(__('Verification code is error.'));
        }

        \DB::transaction(function () use ($user, $request, $verifyData) {
            // 创建用户信息
            $user->username = $request->username;
            $user->phone = $verifyData['phone'] ?? null;
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
        \Cache::forget($request->sms_key);

        $credentials = ['username' => $request->username, 'password' => $request->input('password')];

        $token = auth()->attempt($credentials);

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

        return $this->respondWithToken($token);
    }

    /**
     *
     * 检测注册系统配置
     */
    protected function checkRegisterConfig()
    {
        // 获取注册配置
        $setting = Setting::where('namespace', SettingType::REGISTER)->value('value');

        if ($setting) {

            // 检查注册模式
            if ($setting['register_mode'] == 'closed') {
                $this->response->errorBadRequest(__('Banned registering.'));
            }

            // 检查注册ip限制
            if ($setting['register_limit'] && $setting['register_expires']) {
                $times = Cache::get($this->registerTimesForIp . request()->ip());
                // 如果缓存存在
                if ($times && $times >= $setting['register_limit']) {
                    $this->response->errorBadRequest(__('The same ip can be registered :times times in :minutes minutes.', ['times' => $setting['register_limit'], 'minutes' => $setting['register_expires']]));
                }
            }

            $this->registerConfig = $setting;
        }
    }
}
