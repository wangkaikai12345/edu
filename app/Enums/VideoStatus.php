<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class VideoStatus extends Enum
{
    /**
     * @const string 未切片
     */
    const UNSLICED = 'unsliced';
    /**
     * @const string 切片中
     */
    const SLICING = 'slicing';
    /**
     * @const string 已切片
     */
    const SLICED = 'sliced';

    /**
     * 描述信息
     *
     * @param string $value
     * @return string
     */
    public static function getDescription($value): string
    {
        switch ($value) {
            case self::UNSLICED:
                return '未切片';
                break;
            case self::SLICING:
                return '切片中';
                break;
            case self::SLICED:
                return '已切片';
                break;
            default:
                return self::getKey($value);
        }
    }
}
