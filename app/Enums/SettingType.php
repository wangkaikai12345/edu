<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class SettingType extends Enum
{
    /**
     * @const string
     */
    const QINIU = 'qiniu';
    /**
     * @const string
     */
    const SMS = 'sms';
    /**
     * @const string
     */
    const SITE = 'site';
    /**
     * @const string
     */
    const WECHAT_PAY = 'wechat_pay';
    /**
     * @const string
     */
    const ALI_PAY = 'ali_pay';
    /**
     * @const string
     */
    const EMAIL = 'email';
    /**
     * @const string
     */
    const HEADER_NAV = 'header_nav';
    /**
     * @const string
     */
    const FOOTER_NAV = 'footer_nav';
    /**
     * @const string
     */
    const FRIEND_LINK = 'friend_link';
    /**
     * @const string
     */
    const REGISTER = 'register';
    /**
     * @const string
     */
    const LOGIN = 'login';
    /**
     * @const string
     */
    const AVATAR = 'avatar';
    /**
     * @const string
     */
    const MESSAGE = 'message';

    /**
     * 描述信息
     *
     * @param string $value
     * @return string
     */
    public static function getDescription($value): string
    {
        switch ($value) {
            case self::QINIU:
                return '七牛';
                break;
            case self::SMS:
                return '短信';
                break;
            case self::SITE:
                return '站点';
                break;
            case self::WECHAT_PAY:
                return '微信支付';
                break;
            case self::ALI_PAY:
                return '支付宝支付';
                break;
            case self::EMAIL:
                return '邮箱';
                break;
            case self::HEADER_NAV:
                return '头部导航';
                break;
            case self::FOOTER_NAV:
                return '底部导航';
                break;
            case self::FRIEND_LINK:
                return '友链';
                break;
            case self::REGISTER:
                return '注册';
                break;
            case self::LOGIN:
                return '登录';
                break;
            case self::AVATAR:
                return '头像';
                break;
            case self::MESSAGE:
                return '私信';
                break;
            default:
                return self::getKey($value);
        }
    }
}
