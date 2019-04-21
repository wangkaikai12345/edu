<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class QuestionResultStatus extends Enum
{
    /**
     * @const string 正确
     */
    const RIGHT = 'right';
    /**
     * @const string 错误
     */
    const ERROR = 'error';
    /**
     * @const string 未答题
     */
    const NOANSWER = 'noanswer';
    /**
     * @const string 未批阅
     */
    const NOREAD = 'noread';

    /**
     * 描述信息
     *
     * @param string $value
     * @return string
     */
    public static function getDescription($value): string
    {
        switch ($value) {
            case self::RIGHT:
                return '正确';
                break;
            case self::ERROR:
                return '错误';
                break;
            case self::NOANSWER:
                return '未答题';
                break;
            case self::NOREAD:
                return '未批阅';
                break;
            default:
                return self::getKey($value);
        }
    }
}
