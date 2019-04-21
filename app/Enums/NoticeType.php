<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class NoticeType extends Enum
{
    /**
     * @const string 官方网站
     */
    const WEB = 'web';
    /**
     * @const string 后台网站
     */
    const ADMIN = 'admin';
    /**
     * @const string 教学版本
     */
    const PLAN = 'plan';

    /**
     * 描述信息
     *
     * @param string $value
     * @return string
     */
    public static function getDescription($value): string
    {
        switch ($value) {
            case self::WEB:
                return '官方网站公告';
                break;
            case self::ADMIN:
                return '后台网站公告';
                break;
            case self::PLAN:
                return '教学版本公告';
                break;
            default:
                return self::getKey($value);
        }
    }
}
