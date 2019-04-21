<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class Currency extends Enum
{
    /**
     * 类型：人民币
     */
    const CNY = 'cny';
    /**
     * 类型：虚拟币
     */
    const COIN = 'coin';
    /**
     * 类型：免费
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
            case self::CNY:
                return '人民币';
                break;
            case self::COIN:
                return '虚拟币';
                break;
            case self::FREE:
                return '免费';
                break;
            default:
                return self::getKey($value);
        }
    }
}
