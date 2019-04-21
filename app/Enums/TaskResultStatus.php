<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class TaskResultStatus extends Enum
{
    /**
     * @const string 开始学习
     */
    const START = 'start';
    /**
     * @const string 完成学习
     */
    const FINISH = 'finish';

    /**
     * 描述信息
     *
     * @param string $value
     * @return string
     */
    public static function getDescription($value): string
    {
        switch ($value) {
            case self::START:
                return '学习中';
                break;
            case self::FINISH:
                return '已完成';
                break;
            default:
                return self::getKey($value);
        }
    }
}
