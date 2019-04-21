<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ProductType extends Enum
{
    /**
     * @const string 版本
     */
    const PLAN = 'plan';
    /**
     * @const string 充值
     */
    const RECHARGING = 'recharging';
    /**
     * @const string
     */
    const CLASSROOM = 'classroom';

    /**
     * 描述信息
     *
     * @param string $value
     * @return string
     */
    public static function getDescription($value): string
    {
        switch ($value) {
            case self::PLAN:
                return '课程版本';
                break;
            case self::RECHARGING:
                return '充值额度';
                break;
            case self::CLASSROOM:
                return '班级';
                break;
            default:
                return self::getKey($value);
        }
    }
}
