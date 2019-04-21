<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Sortable\SortableTrait;

/**
 * @SWG\Definition(
 *      definition="Trade",
 *      type="object",
 *      required={},
 *      description="支付交易表模型",
 *      @SWG\Property(property="id",type="integer",readOnly=true),
 *      @SWG\Property(property="title",type="string",default=null,description="订单标题",readOnly=true),
 *      @SWG\Property(property="order_id",type="string",description="订单",readOnly=true),
 *      @SWG\Property(property="trade_uuid",type="string",default=null,description="交易号",readOnly=true),
 *      @SWG\Property(property="status",type="string",enum={"created","paid","refunding","refunded","closed","success","finished"},default="created",description="状态",readOnly=true),
 *      @SWG\Property(property="currency",type="string",description="货币",default="cny",readOnly=true),
 *      @SWG\Property(property="paid_amount",type="integer",description="实际支付金额",readOnly=true),
 *      @SWG\Property(property="seller_id",type="integer",default=0,description="卖家",readOnly=true),
 *      @SWG\Property(property="user_id",type="integer",description="买家",readOnly=true),
 *      @SWG\Property(property="type",type="string",enum={"purchase","recharge","refund"},default="purchase",description="类型：购买/充值/退款",readOnly=true),
 *      @SWG\Property(property="payment",type="string",default=null,description="第三方支付平台",readOnly=true),
 *      @SWG\Property(property="payment_sn",type="string",default=null,description="第三方支付平台交易号",readOnly=true),
 *      @SWG\Property(property="payment_callback",type="string",default=null,description="第三方系统中的支付结果",readOnly=true),
 *      @SWG\Property(property="paid_at",type="string",format="date-time",description="支付时间",readOnly=true),
 *      @SWG\Property(property="created_at",type="string",format="date-time",description="创建时间",readOnly=true),
 *      @SWG\Property(property="updated_at",type="string",format="date-time",description="更新时间",readOnly=true),
 *      @SWG\Property(property="deleted_at",type="string",format="date-time",description="删除时间",readOnly=true),
 * )
 * @SWG\Response(
 *      response="TradePagination",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/Trade")),
 *          @SWG\Property(property="meta",ref="$/definitions/MetaProperty")
 *      )
 * )
 * @SWG\Response(
 *      response="TradeResponse",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/Trade"))
 *      )
 * )
 */
class Trade extends Model
{
    use SortableTrait;

    /**
     * @var string 交易记录
     */
    protected $table = 'trades';

    /**
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @var array
     */
    public $sortable = ['*'];

    /**
     * @var array
     */
    public $searchable = [
        'title',
        'status',
        'currency',
        'paid_amount',
        'seller:username',
        'user:username',
        'type',
        'payment',
        'paid_at',
    ];

    /**
     * @var array
     */
    protected $casts = ['payment_callback' => 'array',];

    /**
     * @var array
     */
    protected $dates = ['paid_at', 'deleted_at'];

    /**
     * 订单
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    /**
     * 用户
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
}
