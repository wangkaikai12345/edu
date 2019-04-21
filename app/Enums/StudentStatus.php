<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class StudentStatus extends Enum
{
    /**
     * @const string 未开始
     */
    const BEGINNING = 'beginning';
    /**
     * @const string 学习中
     */
    const LEARNING = 'learning';
    /**
     * @const string 已学完
     */
    const FINISHED = 'finished';
    /**
     * @const string 已退出
     */
    const EXITED = 'exited';
    /**
     * @const string 已锁定
     */
    const LOCKED = 'locked';

    /**
     * 描述信息
     *
     * @param string $value
     * @return string
     */
    public static function getDescription($value): string
    {
        switch ($value) {
            case self::BEGINNING:
                return '未开始';
                break;
            case self::LEARNING:
                return '学习中';
                break;
            case self::FINISHED:
                return '已完成';
                break;
            case self::EXITED:
                return '已退出';
                break;
            case self::LOCKED:
                return '已锁定';
                break;
            default:
                return self::getKey($value);
        }
    }
}
