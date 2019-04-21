<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class TopicStatus extends Enum
{
    /**
     * @const string 正常
     */
    const QUALIFIED = 'qualified';
    /**
     * @const string 违规
     */
    const VIOLATION = 'violation';

    /**
     * 描述信息
     *
     * @param string $value
     * @return string
     */
    public static function getDescription($value): string
    {
        switch ($value) {
            case self::QUALIFIED:
                return '合格';
                break;
            case self::VIOLATION:
                return '违规';
                break;
            default:
                return self::getKey($value);
        }
    }
}
