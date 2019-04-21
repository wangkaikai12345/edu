<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class UserType extends Enum
{
    /**
     * @const string 超级管理员
     */
    const SUPER_ADMIN = 'super-admin';
    /**
     * @const string 管理员
     */
    const ADMIN = 'admin';
    /**
     * @const string 教师
     */
    const TEACHER = 'teacher';
    /**
     * @const string 学生、一般用户
     */
    const STUDENT = 'student';

    /**
     * 描述信息
     *
     * @param string $value
     * @return string
     */
    public static function getDescription($value): string
    {
        switch ($value) {
            case self::SUPER_ADMIN:
                return '超级管理';
                break;
            case self::ADMIN:
                return '管理';
                break;
            case self::TEACHER:
                return '教师';
                break;
            case self::STUDENT:
                return '用户';
                break;
            default:
                return self::getKey($value);
        }
    }
}
