<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class PaperStatus extends Enum
{
    /**
     * @const string 无效
     */
    const INVALID = 'invalid';
    /**
     * @const string 有效
     */
    const VALID = 'valid';

    /**
     * 描述信息
     *
     * @param string $value
     * @return string
     */
    public static function getDescription($value): string
    {
        switch ($value) {
            case self::INVALID:
                return '无效';
                break;
            case self::VALID:
                return '有效';
                break;
            default:
                return self::getKey($value);
        }
    }
}
