<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class TopicType extends Enum
{
    /**
     * @const string 讨论
     */
    const DISCUSSION = 'discussion';
    /**
     * @const string 问答
     */
    const QUESTION = 'question';

    /**
     * 描述信息
     *
     * @param string $value
     * @return string
     */
    public static function getDescription($value): string
    {
        switch ($value) {
            case self::DISCUSSION:
                return '讨论';
                break;
            case self::QUESTION:
                return '问答';
                break;
            default:
                return self::getKey($value);
        }
    }
}
