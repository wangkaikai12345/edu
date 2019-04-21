<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class CouponType extends Enum
{
    /**
     * 类型：折扣券 当使用折扣券时，应付金额为 原价格 * 折扣
     */
    const DISCOUNT = 'discount';
    /**
     * 类型：代金券 当使用代金券时，应付金额为 原价格 - 抵扣金额
     */
    const VOUCHER = 'voucher';
    /**
     * 类型：试听券 当使用试听券时，应付金额为 0，并应设置试听截止日期
     */
    const AUDITION = 'audition';

    /**
     * 描述信息
     *
     * @param string $value
     * @return string
     */
    public static function getDescription($value): string
    {
        switch ($value) {
            case self::DISCOUNT:
                return '折扣券';
                break;
            case self::VOUCHER:
                return '代金券';
                break;
            case self::AUDITION:
                return '试听券';
                break;
            default:
                return self::getKey($value);
        }
    }
}
