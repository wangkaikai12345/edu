<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ClassroomMode extends Enum
{
    /**
     * @const string 手动
     */
    const HAND = 'hand';
    /**
     * @const string 通关
     */
    const PASS = 'pass';
    /**
     * @const string 通关
     */
    const ALL = 'all';

    /**
     * 描述信息
     *
     * @param string $value
     * @return string
     */
    public static function getDescription($value): string
    {
        switch ($value) {
            case self::HAND:
                return '手动';
                break;
            case self::PASS:
                return '通关';
                break;
            case self::ALL:
                return '全部';
                break;
            default:
                return self::getKey($value);
        }
    }
}
