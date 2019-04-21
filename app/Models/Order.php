<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Traits\HashIdTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Input;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;

/**
 *
 * @SWG\Definition(
 *      definition="Order",
 *      type="object",
 *      required={},
 *      description="订单模型",
 *      @SWG\Property(property="id",type="string",readOnly=true),
 *      @SWG\Property(property="title",type="string",description="订单标题",maxLength=191,readOnly=true),
 *      @SWG\Property(property="price_amount",type="integer",minimum=0,description="订单价格：单位：分",readOnly=true),
 *      @SWG\Property(property="pay_amount",type="integer",minimum=0,description="应付金额：单位：分",readOnly=true),
 *      @SWG\Property(property="currency",type="string",enum={"cny","coin"},description="货币：人民币、虚拟币",default="cny",readOnly=true),
 *      @SWG\Property(property="user_id",type="integer",description="买家",readOnly=true),
 *      @SWG\Property(property="seller_id",type="integer",default=0,description="卖家",readOnly=true),
 *      @SWG\Property(property="status",type="string",enum={"created","paid","refunding","refunded","closed","success","finished","refund_disagree"},description="订单状态：创建、已支付、退款中、已退款、已关闭、已成功、已完成、退款被拒绝",readOnly=true),
 *      @SWG\Property(property="trade_uuid",type="string",description="支付交易号，生成订单时创建（系统维护）",readOnly=true),
 *      @SWG\Property(property="paid_amount",type="integer",description="实际支付金额，支付成功后记录（系统维护）",readOnly=true),
 *      @SWG\Property(property="paid_at",type="string",format="date-time",description="支付时间，支付成功后记录（系统维护）",readOnly=true),
 *      @SWG\Property(property="payment",type="string",description="第三方支付平台:支付宝alipay、微信支付wechat",readOnly=true),
 *      @SWG\Property(property="finished_at",type="string",format="date-time",description="交易成功时间",readOnly=true),
 *      @SWG\Property(property="closed_at",type="string",format="date-time",description="交易关闭时间",readOnly=true),
 *      @SWG\Property(property="closed_message",type="string",description="交易关闭描述",readOnly=true),
 *      @SWG\Property(property="closed_user_id",type="integer",description="关闭订单人",readOnly=true),
 *      @SWG\Property(property="refund_deadline",type="string",format="date-time",description="申请退款的截止日期",readOnly=true),
 *      @SWG\Property(property="created_reason",type="integer",description="订单创建原因, 例如：导入，购买等",readOnly=true),
 *      @SWG\Property(property="product_id",type="integer",description="商品ID",readOnly=true),
 *      @SWG\Property(property="product_type",type="string",description="商品类型",readOnly=true),
 *      @SWG\Property(property="coupon_code",type="string",description="优惠码",readOnly=true),
 *      @SWG\Property(property="coupon_type",type="string",description="优惠码类型",readOnly=true),
 *      @SWG\Property(property="created_at",type="string",format="date-time",description="创建时间",readOnly=true),
 *      @SWG\Property(property="updated_at",type="string",format="date-time",description="更新时间",readOnly=true),
 *      @SWG\Property(property="deleted_at",type="string",format="date-time",description="删除时间",readOnly=true)
 * )
 * @SWG\Response(
 *      response="OrderPagination",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/Order")),
 *          @SWG\Property(property="meta",ref="#/definitions/MetaProperty")
 *      )
 * )
 * @SWG\Response(
 *      response="OrderResponse",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/Order"))
 *      )
 * )
 *
 * // Fillable parameters 表单参数
 * @SWG\Parameter(parameter="OrderForm-pay_amount",name="pay_amount",required=true,in="formData",type="integer",description="应付金额")
 * @SWG\Parameter(parameter="OrderForm-closed_message",name="closed_message",in="formData",type="string",description="关闭原因")
 * @SWG\Parameter(parameter="OrderForm-coupon_code",name="coupon_code",in="formData",type="string",description="优惠码")
 * @SWG\Parameter(parameter="OrderForm-product_type",name="product_type",in="formData",type="string",enum={"plan","recharging","classroom"},description="商品类型：版本、充值、班级")
 * @SWG\Parameter(parameter="OrderForm-product_id",name="product_id",in="formData",type="integer",description="商品ID")
 * @SWG\Parameter(parameter="OrderForm-currency",name="currency",in="formData",type="string",enum={"cny","coin"},description="支付货币：人民币、虚拟币")
 *
 * // Searchable parameters 搜索参数
 * @SWG\Parameter(parameter="OrderQuery-title",name="title",in="query",type="string",description="订单标题(默认为商品标题)")
 * @SWG\Parameter(parameter="OrderQuery-price_amount",name="price_amount",in="query",type="integer",description="订单价格")
 * @SWG\Parameter(parameter="OrderQuery-pay_amount",name="pay_amount",in="query",type="integer",description="应付金额")
 * @SWG\Parameter(parameter="OrderQuery-currency",name="currency",in="query",type="string",enum={"cny","coin"},description="货币：人民币、虚拟币")
 * @SWG\Parameter(parameter="OrderQuery-user:username",name="user:username",in="query",type="string",description="买家账户")
 * @SWG\Parameter(parameter="OrderQuery-user_id",name="user_id",in="query",type="integer",description="买家ID")
 * @SWG\Parameter(parameter="OrderQuery-status",name="status",in="query",type="string",enum={"created","paid","refunding","refunded","closed","success","finished","refund_disagree"},description="未支付、已支付、退款中、已退款、已关闭、已成功、已完结、退款被拒绝")
 * @SWG\Parameter(parameter="OrderQuery-trade_uuid",name="trade_uuid",in="query",type="string",description="交易号")
 * @SWG\Parameter(parameter="OrderQuery-paid_amount",name="paid_amount",in="query",type="string",description="实付金额")
 * @SWG\Parameter(parameter="OrderQuery-paid_at",name="paid_at",in="query",type="string",description="支付时间")
 * @SWG\Parameter(parameter="OrderQuery-payment",name="payment",in="query",type="string",enum={"alipay","wechat","coin","free"},description="支付方：支付宝、微信、虚拟币、免费")
 * @SWG\Parameter(parameter="OrderQuery-finished_at",name="finished_at",in="query",type="string",description="订单完结时间（不能对订单任何写操作）")
 * @SWG\Parameter(parameter="OrderQuery-closed_at",name="closed_at",in="query",type="string",description="订单取消时间")
 * @SWG\Parameter(parameter="OrderQuery-refund_deadline",name="refund_deadline",in="query",type="string",description="退款截止日期")
 * @SWG\Parameter(parameter="OrderQuery-product_type",name="product_type",in="query",type="string",enum={"plan","recharging"},description="商品类型：课程订单、充值订单")
 * @SWG\Parameter(parameter="OrderQuery-coupon_code",name="coupon_code",in="query",type="string",description="优惠码")
 * @SWG\Parameter(parameter="OrderQuery-created_at",name="created_at",in="query",type="string",description="创建时间")
 *
 * // sortable parameters 排序参数
 * @SWG\Parameter(
 *     parameter="Order-sort",
 *     name="sort",
 *     in="query",
 *     type="string",
 *     description="使用方法见 sortable；[price_amount,pay_amount,paid_amount,paid_at,finished_at,refund_deadline,created_at]",
 * )
 *
 * // Include parameters 关联参数
 * @SWG\Parameter(
 *     parameter="Order-include",
 *     name="include",
 *     in="query",
 *     type="string",
 *     description="使用方法见 Include；{user:买家,seller:卖家,trade:交易记录,product:商品,refund:退款记录,coupon:优惠详情}",
 * )
 */
class Order extends Model
{
    use SearchableTrait, SortableTrait, SoftDeletes;

    /**
     * @var string 订单
     */
    protected $table = 'orders';

    /**
     * @var array
     */
    public $sortable = [
        'price_amount',
        'pay_amount',
        'paid_amount',
        'paid_at',
        'finished_at',
        'refund_deadline',
        'created_at',
    ];

    /**
     * @var array 搜索字段
     */
    protected $searchable = [
        'title',
        'price_amount',
        'pay_amount',
        'currency',
        'user_id',
        'user:username',
        'status',
        'trade_uuid',
        'paid_amount',
        'paid_at',
        'payment',
        'finished_at',
        'closed_at',
        'refund_deadline',
        'product_type',
        'coupon_code',
        'created_at'
    ];

    /**
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @var array
     */
    protected $dates = ['paid_at', 'closed_at', 'finished_at', 'refund_deadline', 'deleted_at'];

    /**
     * @var string
     */
    public $defaultSortCriteria = 'created_at,desc';

    /**
     * @var array
     */
    protected $casts = [
        'product_id' => 'integer',
        'price_amount' => 'integer',
        'pay_amount' => 'integer',
    ];

    /**
     * @var integer 新订单过期时间（若在该时间支付未成功则自动设置为关闭）单位：分钟
     */
    public static $expires = 30;

    /**
     * @var integer 新订单允许退款天数
     */
    public static $refundDays = 7;

    /**
     * 买家
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * 卖家
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id', 'id');
    }

    /**
     * 关闭人
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function closed_user()
    {
        return $this->belongsTo(User::class, 'closed_user_id', 'id');
    }

    /**
     * 交易记录
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function trade()
    {
        return $this->hasOne(Trade::class, 'order_id', 'id');
    }

    /**
     * 商品
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function product()
    {
        return $this->morphTo();
    }

    /**
     * 退款
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function refund()
    {
        return $this->hasOne(Refund::class, 'order_id', 'id');
    }

    /**
     * 优惠券
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_code', 'code');
    }

    /**
     * 时间段内的订单
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Carbon\Carbon $start
     * @param \Carbon\Carbon $end
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function scopeNew($query, Carbon $start, Carbon $end)
    {
        return $query->whereBetween('created_at', [$start, $end])->where('status', OrderStatus::FINISHED);
    }



    /**
     * Applies filters.
     *
     * @param Builder $builder query builder
     * @param array   $query   query parameters to use for search - Input::all() is used by default
     */
    public function scopeFiltered( $builder, array $query = [])
    {
        $query = (array)($query ?: array_filter(Input::all()));
        $mode = $this->getQueryMode($query);
        $query = $this->filterNonSearchableParameters($query);
        $constraints = $this->getConstraints($builder, $query);

        $this->applyConstraints($builder, $constraints, $mode);
    }
}
