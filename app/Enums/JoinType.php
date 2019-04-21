<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class JoinType extends Enum
{
    /**
     * @const string 购买课程
     */
    const PURCHASE = 'purchase';

    /**
     * @const string 试听券进入
     */
    const AUDITION = 'audition';

    /**
     * @const string 免费进入
     */
    const FREE = 'free';

    /**
     * @const string 内部
     */
    const INSIDE = 'inside';

    /**
     * 描述信息
     *
     * @param string $value
     * @return string
     */
    public static function getDescription($value): string
    {
        switch ($value) {
            case self::PURCHASE:
                return '购买课程';
                break;
            case self::AUDITION:
                return '试听';
                break;
            case self::FREE:
                return '免费加入';
                break;
            case self::INSIDE:
                return 'inside';
                break;
            default:
                return self::getKey($value);
        }
    }
}
