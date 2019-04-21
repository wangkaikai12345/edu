<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class FavoriteType extends Enum
{
    /**
     * @const string 课程
     */
    const COURSE = 'course';

    /**
     * @const string 话题
     */
    const TOPIC = 'topic';

    /**
     * @const string 笔记
     */
    const NOTE = 'note';

    /**
     * 描述信息
     *
     * @param string $value
     * @return string
     */
    public static function getDescription($value): string
    {
        switch ($value) {
            case self::COURSE:
                return '课程';
                break;
            case self::TOPIC:
                return '话题';
                break;
            case self::NOTE:
                return '笔记';
                break;
            default:
                return self::getKey($value);
        }
    }
}
