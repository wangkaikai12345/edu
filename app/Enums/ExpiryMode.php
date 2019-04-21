<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ExpiryMode extends Enum
{
    /**
     * @const string 时间范围（确切时间范围）
     */
    const PERIOD = 'period';
    /**
     * @const string 有效时间（加入后指定的有效时长，单位：天数）
     */
    const VALID = 'valid';
    /**
     * @const string 永久有效
     */
    const FOREVER = 'forever';

    /**
     * 描述信息
     *
     * @param string $value
     * @return string
     */
    public static function getDescription($value): string
    {
        switch ($value) {
            case self::PERIOD:
                return '时间范围';
                break;
            case self::VALID:
                return '有效天数';
                break;
            case self::FOREVER:
                return '永久有效';
                break;
            default:
                return self::getKey($value);
        }
    }
}
