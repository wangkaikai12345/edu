<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class QuestionType extends Enum
{
    /**
     * @const string 单选
     */
    const SINGLE = 'single';
    /**
     * @const string 多选
     */
    const MULTIPLE = 'multiple';
    /**
     * @const string 主观
     */
    const ANSWER = 'answer';

    /**
     * 描述信息
     *
     * @param string $value
     * @return string
     */
    public static function getDescription($value): string
    {
        switch ($value) {
            case self::SINGLE:
                return '单选';
                break;
            case self::MULTIPLE:
                return '多选';
                break;
            case self::ANSWER:
                return '主观';
                break;
            default:
                return self::getKey($value);
        }
    }
}
