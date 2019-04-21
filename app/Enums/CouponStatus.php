<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class CouponStatus extends Enum
{
    /**
     * @const string 未使用
     */
    const UNUSED = 'unused';

    /**
     * @const string 已激活
     */
    const ACTIVATED = 'activated';

    /**
     * @const string 已使用
     */
    const USED = 'used';

    /**
     * 描述信息
     *
     * @param string $value
     * @return string
     */
    public static function getDescription($value): string
    {
        switch ($value) {
            case self::UNUSED:
                return '未使用';
                break;
            case self::ACTIVATED:
                return '已激活';
                break;
            case self::USED:
                return '已使用';
                break;
            default:
                return self::getKey($value);
        }
    }
}
