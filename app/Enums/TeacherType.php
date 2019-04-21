<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class TeacherType extends Enum
{
    /**
     * @const string 班主任
     */
    const HEAD = 'head';
    /**
     * @const string 讲师
     */
    const TEACHER = 'teacher';
    /**
     * @const string 助教
     */
    const ASSISTANT = 'assistant';

    /**
     * 描述信息
     *
     * @param string $value
     * @return string
     */
    public static function getDescription($value): string
    {
        switch ($value) {
            case self::HEAD:
                return '班主任';
                break;
            case self::TEACHER:
                return '教师';
                break;
            case self::ASSISTANT:
                return '助教';
                break;
            default:
                return self::getKey($value);
        }
    }
}
