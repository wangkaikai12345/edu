<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class Payment extends Enum
{
    /**
     * 类型：支付宝
     */
    const ALIPAY = 'alipay';
    /**
     * 类型：微信支付
     */
    const WECHAT = 'wechat';
    /**
     * 类型：虚拟币支付
     */
    const COIN = 'coin';
    /**
     * 类型：免费标识
     */
    const FREE = 'free';

    /**
     * 描述信息
     *
     * @param string $value
     * @return string
     */
    public static function getDescription($value): string
    {
        switch ($value) {
            case self::ALIPAY:
                return '支付宝';
                break;
            case self::WECHAT:
                return '微信支付';
                break;
            case self::COIN:
                return '虚拟币支付';
                break;
            case self::FREE:
                return '免费';
            default:
                return self::getKey($value);
        }
    }

}
