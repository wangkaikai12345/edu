<?php

namespace App\Http\Controllers\Front;

use App\Enums\SettingType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Front\SmsRequest;
use Facades\App\Models\Setting;
use Cache;
use Overtrue\EasySms\EasySms;
use Overtrue\EasySms\Exceptions\NoGatewayAvailableException;

class SmsController extends Controller
{
    /**
     * 发送短信验证码
     *
     * @param $type
     * @param SmsRequest $request
     * @return mixed
     * @author 王凯
     */
    public function send($type, SmsRequest $request)
    {
        // 获取短信配置
        if (!($aliyunSetting = Setting::where('namespace', SettingType::SMS)->value('value'))) return ajax('400', '短信服务未开通');

        // 判断是否未验证手机
        if (strtolower($type == 'verify')) {

            if (!auth('web')->check()) return ajax('401', '请登陆');
//            if (auth('web')->user()->is_phone_verified) return ajax('400', '手机已经绑定');
            // 密码验证
            if (!\Hash::check($request->password, auth('web')->user()->getAuthPassword())) {
                return ajax('400', '登陆密码不正确');
            }
        }

        // 生成随机验证码
        $smsKey = 'verification_' . str_random(15);
        $code = mt_rand(1000, 9999);

        // 生成基本配置
        $config = config('sms');
        data_set($config, 'gateways.aliyun', [
            'access_key_id' => $aliyunSetting['ak'],
            'access_key_secret' => $aliyunSetting['sk'],
            'sign_name' => $aliyunSetting['sign_name'],
        ]);

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

            return ajax('400', __('SMS error.'));
        }

        return ajax('200', '短信发送成功', [
            'verification_key' => $smsKey,
            'expired_at' => now()->addMinutes($expires)->toDateTimeString()
        ]);
    }
}
