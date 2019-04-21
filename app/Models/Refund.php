<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;

/**
 * @SWG\Definition(
 *      definition="Refund",
 *      type="object",
 *      required={},
 *      description="退款模型",
 *      @SWG\Property(property="id",type="integer",readOnly=true),
 *      @SWG\Property(property="title",type="string",description="订单标题",readOnly=true),
 *      @SWG\Property(property="order_id",type="integer",description="订单",readOnly=true),
 *      @SWG\Property(property="status",type="string",enum={"created","refunding","refunded","closed","refund_disagree","refund_failed"},description="退款状态：已提交、退款中、已退款、已关闭、退款被拒绝、退款失败"),
 *      @SWG\Property(property="payment",type="string",enum={"alipay","wechat","coin"},description="支付方式：支付宝/微信/虚拟币",readOnly=true),
 *      @SWG\Property(property="payment_sn",type="string",description="支付平台交易号码",readOnly=true),
 *      @SWG\Property(property="user_id",type="integer",description="用户"),
 *      @SWG\Property(property="reason",type="string",description="用户填写退款原因"),
 *      @SWG\Property(property="currency",type="string",enum={"coin","cny"},description="货币类型",readOnly=true),
 *      @SWG\Property(property="applied_amount",type="integer",description="申请退款金额，单位：分"),
 *      @SWG\Property(property="refunded_amount",type="integer",description="实际退款金额，单位：分"),
 *      @SWG\Property(property="handled_at",type="string",format="date-time",description="处理时间",readOnly=true),
 *      @SWG\Property(property="handler_id",type="integer",description="退款操作人"),
 *      @SWG\Property(property="handled_reason",type="string",description="处理原因",default=null),
 *      @SWG\Property(property="created_at",type="string",format="date-time",description="创建时间",readOnly=true),
 *      @SWG\Property(property="updated_at",type="string",format="date-time",description="更新时间",readOnly=true)
 * )
 * @SWG\Response(
 *      response="RefundPagination",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/Refund")),
 *          @SWG\Property(property="meta",ref="$/definitions/MetaProperty")
 *      )
 * )
 * @SWG\Response(
 *      response="RefundResponse",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/Refund"))
 *      )
 * )
 *
 * // Fillable parameters 表单参数
 * @SWG\Parameter(parameter="RefundForm-applied_amount",name="applied_amount",required=true,in="formData",type="integer",description="申请退款金额，不能超过支付金额")
 * @SWG\Parameter(parameter="RefundForm-reason",name="reason",in="formData",type="integer",description="退款原因")
 *
 * // Searchable parameters 搜索参数
 * @SWG\Parameter(parameter="RefundQuery-title",name="title",in="query",type="string",description="订单标题")
 * @SWG\Parameter(parameter="RefundQuery-order_id",name="order_id",in="query",type="integer",description="订单ID")
 * @SWG\Parameter(parameter="RefundQuery-status",name="status",in="query",type="string",enum={"created","refunding","refunded","closed","refund_disagree","refund_failed"},description="退款状态：已提交、退款中、已退款、已关闭、退款被拒绝、退款失败")
 * @SWG\Parameter(parameter="RefundQuery-payment",name="payment",in="query",type="string",enum={"alipay","wechat","coin"},description="支付方式：支付宝/微信/虚拟币")
 * @SWG\Parameter(parameter="RefundQuery-payment_sn",name="payment_sn",in="query",type="string",description="交易号")
 * @SWG\Parameter(parameter="RefundQuery-user_id",name="user_id",in="query",type="integer",description="退款申请人ID")
 * @SWG\Parameter(parameter="RefundQuery-user:username",name="user:username",in="query",type="string",description="退款申请人用户名")
 * @SWG\Parameter(parameter="RefundQuery-currency",name="currency",in="query",type="string",enum={"cny","coin"},description="货币类型：人民币、虚拟币")
 * @SWG\Parameter(parameter="RefundQuery-applied_amount",name="applied_amount",in="query",type="integer",description="申请退款的金额")
 * @SWG\Parameter(parameter="RefundQuery-refunded_amount",name="refunded_amount",in="query",type="integer",description="实际退款金额")
 * @SWG\Parameter(parameter="RefundQuery-handled_at",name="handled_at",in="query",type="string",description="退款处理时间")
 * @SWG\Parameter(parameter="RefundQuery-handler_id",name="handler_id",in="query",type="integer",description="退款处理人ID")
 * @SWG\Parameter(parameter="RefundQuery-handler:username",name="handler:username",in="query",type="integer",description="退款处理人账户")
 * @SWG\Parameter(parameter="RefundQuery-created_at",name="created_at",in="query",type="string",description="申请时间/创建时间")
 *
 * // sortable parameters 排序参数
 * @SWG\Parameter(
 *     parameter="Refund-sort",
 *     name="sort",
 *     in="query",
 *     type="string",
 *     description="使用方法见 sortable；[]",
 * )
 *
 * // Include parameters 关联参数
 * @SWG\Parameter(
 *     parameter="Refund-include",
 *     name="include",
 *     in="query",
 *     type="string",
 *     description="使用方法见 Include；{order:附属订单,user:申请人,handler:审核人}",
 * )
 */
class Refund extends Model
{
    use SoftDeletes, SortableTrait,SearchableTrait;

    /**
     * @var string 退款
     */
    protected $table = 'refunds';

    /**
     * @var array
     */
    public $sortable = [
        'applied_amount',
        'refunded_amount',
        'handled_at',
        'created_at'
    ];

    /**
     * @var array
     */
    public $searchable = [
        'title',
        'order_id',
        'status',
        'payment',
        'payment_sn',
        'currency',
        'applied_amount',
        'refunded_amount',
        'handled_at',
        'handler_id',
        'handler:username',
        'created_at',
    ];

    /**
     * @var string sortable 默认排序
     */
    public $defaultSortCriteria = 'created_at,desc';

    /**
     * @var array
     */
    protected $dates = ['handled_at', 'deleted_at'];

    /**
     * @var array
     */
    protected $casts = [
        'applied_amount' => 'integer',
        'refunded_amount' => 'integer',
        'payment_callback' => 'array',
    ];

    /**
     * @var array
     */
    protected $fillable = ['reason', 'applied_amount'];

    /**
     * 买家
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 退款处理人
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function handler()
    {
        return $this->belongsTo(User::class, 'handler_id', 'id');
    }

    /**
     * 订单
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * 交易记录
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function trade()
    {
        return $this->belongsTo(Trade::class, 'payment_sn', 'payment_sn');
    }
}
