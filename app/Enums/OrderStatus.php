<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class OrderStatus extends Enum
{
    /**
     * @const string 已创建（新创建到已支付期间的状态）
     */
    const CREATED = 'created';
    /**
     * @const string 已支付（支付成功到允许退款期间（允许退款可自定义，默认为 7 日）状态）
     */
    const PAID = 'paid';
    /**
     * @const string 退款中（申请退款后到审核完毕区间）
     */
    const REFUNDING = 'refunding';
    /**
     * @const string 已退款（退款申请审核完毕，退款到达后）
     */
    const REFUNDED = 'refunded';
    /**
     * @const string 已关闭（退款关闭后）
     */
    const CLOSED = 'closed';
    /**
     * @const string 已成功
     */
    const SUCCESS = 'success';
    /**
     * @const string 已完成（退款截止日期后，一般为 七日）
     */
    const FINISHED = 'finished';
    /**
     * @const string 退款已拒绝（退款）
     */
    const REFUND_DISAGREE = 'refund_disagree';
    /**
     * @const string 退款失败
     */
    const REFUND_FAILED = 'refund_failed';
    /**
     * @const string 异常订单（订单金额与实际支付金额不对等）
     */
    const ABNORMAL = 'abnormal';

    /**
     * 描述信息
     *
     * @param string $value
     * @return string
     */
    public static function getDescription($value): string
    {
        switch ($value) {
            case self::CREATED:
                return '新创建';
                break;
            case self::PAID:
                return '已支付';
                break;
            case self::REFUNDING:
                return '退款申请中';
                break;
            case self::REFUNDED:
                return '已退款';
                break;
            case self::CLOSED:
                return '已关闭';
                break;
            case self::SUCCESS:
                return '已成功';
                break;
            case self::FINISHED:
                return '已完成';
                break;
            case self::REFUND_DISAGREE:
                return '退款被拒绝';
                break;
                case self::REFUND_FAILED:
                return '退款失败';
                break;
            case self::ABNORMAL:
                return '异常订单';
            default:
                return self::getKey($value);
        }
    }

}
