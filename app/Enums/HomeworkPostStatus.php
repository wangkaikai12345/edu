<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class HomeworkPostStatus extends Enum
{
    /**
     * @const string 草稿状态
     */
    const  READING= 'reading';
    /**
     * @const string 已发布状态
     */
    const READED = 'readed';

    /**
     * 描述信息
     *
     * @param string $value
     * @return string
     */
    public static function getDescription($value): string
    {
        switch ($value) {
            case self::READING:
                return '批阅中';
                break;
            case self::READED:
                return '已批阅';
                break;
            default:
                return self::getKey($value);
        }
    }
}
