<?php

namespace App\Http\Controllers\Web;

use App\Enums\SettingType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\SmsRequest;
use Facades\App\Models\Setting;
use Cache;
use Overtrue\EasySms\EasySms;
use Overtrue\EasySms\Exceptions\NoGatewayAvailableException;

class SmsController extends Controller
{
    /**
     * @SWG\Tag(name="web/verification",description="短信验证码")
     */

    /**
     * @SWG\Post(
     *  path="/sms/{type}",
     *  tags={"web/verification"},
     *  summary="短信验证码",
     *  description="注册、重置密码、登录、身份验证等",
     *  @SWG\Parameter(in="path",required=true,name="type",type="string",enum={"register","password","login","verify"},description="注册register、重置密码password、登录login、身份验证verify（手机绑定）"),
     *  @SWG\Parameter(in="formData",name="captcha_key",type="string",description="图形验证码Key"),
     *  @SWG\Parameter(in="formData",name="captcha_code",type="string",description="图形验证码Code"),
     *  @SWG\Parameter(in="formData",name="phone",type="string",description="手机号"),
     *  @SWG\Response(response=201,description="发送成功",@SWG\Schema(
     *      @SWG\Property(property="sms_key",description="短信验证码 Key"),
     *      @SWG\Property(property="expired_at",description="过期时间")
     *  ))
     * )
     */
    public function send($type, SmsRequest $request)
    {
        if (!($captchaData = Cache::get($request->captcha_key))) {
            $this->response->errorBadRequest(__('Verification code has been expired.'));
        }

        if ($captchaData['code'] != $request->captcha_code) {
            $this->response->errorBadRequest(__('Verification code is error.'));
        }

        // 获取短信配置
        if (!($aliyunSetting = Setting::where('namespace', SettingType::SMS)->value('value'))) {
            $this->response->errorBadRequest(__('SMS is not supported.'));
        }

        if (strtolower($type == 'verify') && !auth()->check()) {
            abort(401);
        }

        // 生成随机验证码
        $smsKey = 'sms_' . str_random(15);
        $code = mt_rand(1000, 9999);

        // 生成基本配置
        $config = config('sms');
        data_set($config, 'gateways.aliyun', [
            'access_key_id' => $aliyunSetting['ak'],
            'access_key_secret' => $aliyunSetting['sk'],
            'sign_name' => $aliyunSetting['sign_name'],
        ]);

        if (strtolower($type) === 'verify') {
            if (!($user = auth()->user())) {
                abort(401);
            }

            if ($user->is_phone_verified) {
                $this->response->errorBadRequest(__('Already bound.'));
            }
        }

        // 模板
        $template = data_get($aliyunSetting, strtolower($type) . '_template_code');

        // 获取配置中的固定的变量信息
        $variable = data_get($aliyunSetting, 'variable', []);
        $variable['code'] = $code;
        // 过期时间
        $expires = data_get($aliyunSetting, 'expires', 10);

        try {
            $easySms = new EasySms($config);
            $easySms->send($request->phone, [
                'template' => $template,
                'data' => $variable
            ]);

            // 加入缓存
            Cache::put($smsKey, ['phone' => $request->phone, 'code' => $code], now()->addMinutes($expires));
        } catch (NoGatewayAvailableException $exception) {
            $exception = $exception->getLastException();
            \Log::useFiles(storage_path('logs/sms.log'));
            \Log::info($request->phone);
            \Log::error($exception);
            $this->response->errorBadRequest(__('SMS error.'));
        }

        Cache::forget($request->captcha_key);

        return $this->response->array([
            'sms_key' => $smsKey,
            'expired_at' => now()->addMinutes($expires)->toDateTimeString()
        ])->setStatusCode(201);
    }
}

