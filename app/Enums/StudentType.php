<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class StudentType extends Enum
{
    /**
     * @const string 试听学员
     */
    const AUDITION = 'audition';
    /**
     * @const string 正式学员
     */
    const OFFICIAL = 'official';

    /**
     * @const string 内部学员
     */
    const INSIDE = 'inside';

    /**
     * 描述信息
     *
     * @param string $value
     * @return string
     */
    public static function getDescription($value): string
    {
        switch ($value) {
            case self::AUDITION:
                return '试听学员';
                break;
            case self::OFFICIAL:
                return '正式学员';
                break;
            case self::INSIDE:
                return '内部学员';
                break;
            default:
                return self::getKey($value);
        }
    }
}
