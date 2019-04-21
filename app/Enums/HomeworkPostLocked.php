<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class HomeworkPostLocked extends Enum
{
    /**
     * @const string 锁定状态
     */
    const LOCKED = 'locked';
    /**
     * @const string 启用状态
     */
    const OPEN = 'open';

    /**
     * 描述信息
     *
     * @param string $value
     * @return string
     */
    public static function getDescription($value): string
    {
        switch ($value) {
            case self::LOCKED:
                return '锁定';
                break;
            case self::OPEN:
                return '启用';
                break;
            default:
                return self::getKey($value);
        }
    }
}
