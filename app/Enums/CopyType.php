<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class CopyType extends Enum
{
    /**
     * @const string 版本复制
     */
    const PLAN = 'plan';

    /**
     * @const string 班级复制
     */
    const CLASSROOM = 'classroom';


    /**
     * 描述信息
     *
     * @param string $value
     * @return string
     */
    public static function getDescription($value): string
    {
        switch ($value) {
            case self::PLAN:
                return '版本复制';
                break;
            case self::CLASSROOM:
                return '班级复制';
                break;
            default:
                return self::getKey($value);
        }
    }
}
