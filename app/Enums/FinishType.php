<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class FinishType extends Enum
{
    /**
     * @const string 指定结尾
     */
    const END = 'end';

    /**
     * @const string 指定时长
     */
    const TIME = 'time';

    /**
     * 描述信息
     *
     * @param string $value
     * @return string
     */
    public static function getDescription($value): string
    {
        switch ($value) {
            case self::TIME:
                return '指定时长';
                break;
            case self::END:
                return '指定结尾';
                break;
            default:
                return self::getKey($value);
        }
    }

}
