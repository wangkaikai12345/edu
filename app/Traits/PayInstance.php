<?php
/**
 * Created by PhpStorm.
 * User: wangbaolong
 * Date: 2018/8/23
 * Time: 14:11
 */

namespace App\Traits;

use App\Enums\Payment;
use App\Enums\SettingType;
use Facades\App\Models\Setting;
use Yansongda\Pay\Pay;

trait PayInstance
{
    /**
     * 创建一个支付服务实例
     *
     * @param string $type
     * @return \Yansongda\Pay\Gateways\Alipay|\Yansongda\Pay\Gateways\Wechat
     */
    protected function payInstance(string $type)
    {
        switch ($type) {
            case Payment::ALIPAY:
                $config = Setting::namespace(SettingType::ALI_PAY);
                $config = array_merge(config('pay.alipay'), $config);
                $instance = Pay::alipay($config);
                break;
            case Payment::WECHAT:
                $config = Setting::namespace(SettingType::WECHAT_PAY);
                $config = array_merge(config('pay.wechat'), $config);
                $instance = Pay::wechat($config);
                break;
            default:
                abort(400, __('Unknown payment.'));
        }

        return $instance;
    }
}