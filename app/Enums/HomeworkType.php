<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class HomeworkType extends Enum
{
    /**
     * @const string 草稿状态
     */
    const HOMEWORK = 'homework';
    /**
     * @const string 已发布状态
     */
    const EXERCISE = 'practice';

    /**
     * 描述信息
     *
     * @param string $value
     * @return string
     */
    public static function getDescription($value): string
    {
        switch ($value) {
            case self::HOMEWORK:
                return '作业';
                break;
            case self::EXERCISE:
                return '练习';
                break;
            default:
                return self::getKey($value);
        }
    }
}
