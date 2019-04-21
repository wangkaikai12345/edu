<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class SerializeMode extends Enum
{
    /**
     * @const string 无
     */
    const NONE = 'none';
    /**
     * @const string 连载中
     */
    const SERIALIZED = 'serialized';
    /**
     * @const string 连载完成
     */
    const FINISHED = 'finished';

    /**
     * 描述信息
     *
     * @param string $value
     * @return string
     */
    public static function getDescription($value): string
    {
        switch ($value) {
            case self::NONE:
                return '更新中';
                break;
            case self::SERIALIZED:
                return '连载中';
                break;
            case self::FINISHED:
                return '已完结';
                break;
            default:
                return self::getKey($value);
        }
    }
}
