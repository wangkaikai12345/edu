<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class LearnMode extends Enum
{
    /**
     * @const string 解锁式学习
     */
    const LOCK = 'lock';
    /**
     * @const string 自由式学习
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
            case self::LOCK:
                return '解锁式学习';
                break;
            case self::FREE:
                return '自由式学习';
                break;
            default:
                return self::getKey($value);
        }
    }

}
