<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class Status extends Enum
{
    /**
     * @const string 草稿状态
     */
    const DRAFT = 'draft';
    /**
     * @const string 已发布状态
     */
    const PUBLISHED = 'published';
    /**
     * @const string 已关闭状态
     */
    const CLOSED = 'closed';

    /**
     * 描述信息
     *
     * @param string $value
     * @return string
     */
    public static function getDescription($value): string
    {
        switch ($value) {
            case self::DRAFT:
                return '未发布';
                break;
            case self::PUBLISHED:
                return '已发布';
                break;
            case self::CLOSED:
                return '已关闭';
                break;
            default:
                return self::getKey($value);
        }
    }
}
