<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ClassroomResultStatus extends Enum
{
    /**
     * @const string 学习中
     */
    const LEARN = 'learn';
    /**
     * @const string 作业审批
     */
    const APPROVAL = 'approval';
    /**
     * @const string 通关
     */
    const PASS = 'pass';

    /**
     * 描述信息
     *
     * @param string $value
     * @return string
     */
    public static function getDescription($value): string
    {
        switch ($value) {
            case self::LEARN:
                return '学习中';
                break;
            case self::APPROVAL:
                return '作业审批';
                break;
            case self::PASS:
                return '通关';
                break;
            default:
                return self::getKey($value);
        }
    }
}
