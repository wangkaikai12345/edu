<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class TradeType extends Enum
{
    /**
     * @const string 购买
     */
    const PURCHASE = 'purchase';
    /**
     * @const string 重置
     */
    const RECHARGE = 'recharge';
    /**
     * @const string 退款
     */
    const REFUND = 'refund';

    /**
     * 描述信息
     *
     * @param string $value
     * @return string
     */
    public static function getDescription($value): string
    {
        switch ($value) {
            case self::PURCHASE:
                return '购买';
                break;
            case self::RECHARGE:
                return '充值';
                break;
            case self::REFUND:
                return '退款';
                break;
            default:
                return self::getKey($value);
        }
    }
}
