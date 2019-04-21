<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class Gender extends Enum
{
    /**
     * @const string 男
     */
    const MALE = 'male';
    /**
     * @const string 女
     */
    const FEMALE = 'female';
    /**
     * @const string 保密
     */
    const SECRET = 'secret';

    /**
     * 描述信息
     *
     * @param string $value
     * @return string
     */
    public static function getDescription($value): string
    {
        switch ($value) {
            case self::MALE:
                return '男';
                break;
            case self::FEMALE:
                return '女';
                break;
            case self::SECRET:
                return '保密';
                break;
            default:
                return self::getKey($value);
        }
    }
}
