<?php

namespace App\Models;

use App\Enums\CouponStatus;
use App\Enums\CouponType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Input;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;

/**
 * @SWG\Definition(
 *      definition="Coupon",
 *      type="object",
 *      required={"coin"},
 *      description="优惠券模型",
 *      @SWG\Property(property="code",type="string",description="优惠码",readOnly=true),
 *      @SWG\Property(property="batch",type="string",description="批次码",readOnly=true),
 *      @SWG\Property(property="type",type="string",enum={"discount","voucher","audition"},description="折扣券/代金券/试听券",default="discount"),
 *      @SWG\Property(property="value",type="integer",description="优惠额度"),
 *      @SWG\Property(property="expired_at",type="integer",description="优惠券过期时间",default=null),
 *      @SWG\Property(property="user_id",type="string",format="date-time",description="用户ID",default=null),
 *      @SWG\Property(property="consumer_id",type="string",format="date-time",description="消费者/指定的消费者",default=null),
 *      @SWG\Property(property="consumed_at",type="string",format="date-time",description="消费时间",default=null),
 *      @SWG\Property(property="status",type="string",enum={"unused","activated","used"},description="状态：未使用/已激活/已使用",default="unused",readOnly=true),
 *      @SWG\Property(property="product_id",type="integer",description="指定商品ID",default=null),
 *      @SWG\Property(property="product_type",type="integer",description="指定商品类型",default=null),
 *      @SWG\Property(property="remark",type="string",description="备注信息",readOnly=true),
 *      @SWG\Property(property="created_at",type="string",format="date-time",description="创建时间",readOnly=true),
 *      @SWG\Property(property="updated_at",type="string",format="date-time",description="更新时间",readOnly=true)
 * )
 * @SWG\Response(
 *   response="CouponPagination",
 *   description="",
 *   @SWG\Schema(
 *     @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/Coupon")),
 *     @SWG\Property(property="meta",ref="$/definitions/MetaProperty")
 *   )
 * )
 * @SWG\Response(
 *   response="CouponResponse",
 *   description="",
 *   @SWG\Schema(
 *     @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/Coupon"))
 *   )
 * )
 *
 * // Fillable parameters 表单参数
 * @SWG\Parameter(parameter="CouponForm-type",name="type",required=true,in="formData",type="string",enum={"discount","voucher","audition"},description="折扣券/代金券/试听券")
 * @SWG\Parameter(parameter="CouponForm-value",name="value",required=true,in="formData",type="integer",description="折扣券/代金券/试听券 额度")
 * @SWG\Parameter(parameter="CouponForm-expired_at",name="expired_at",required=true,in="formData",type="string",format="date-time",description="有效期")
 * @SWG\Parameter(parameter="CouponForm-consumer_id",name="consumer_id",in="formData",type="string",description="指定适用用户")
 * @SWG\Parameter(parameter="CouponForm-product_type",name="product_type",in="formData",type="string",enum={"plan","recharging"},description="课程版本订单/充值订单")
 * @SWG\Parameter(parameter="CouponForm-product_id",name="product_id",in="formData",type="string",description="商品ID")
 * @SWG\Parameter(parameter="CouponForm-remark",name="remark",in="formData",type="string",description="备注")
 *
 * // Searchable parameters 搜索参数
 * @SWG\Parameter(parameter="CouponQuery-code",name="code",in="query",type="string",description="优惠码")
 * @SWG\Parameter(parameter="CouponQuery-type",name="type",in="query",type="string",enum={"discount","voucher","audition"},description="折扣券/代金券/试听券")
 * @SWG\Parameter(parameter="CouponQuery-status",name="status",in="query",type="string",enum={"unused","sent","used"},description="状态：未使用/已赠送/已使用")
 * @SWG\Parameter(parameter="CouponQuery-user:username",name="user:username",in="query",type="string",description="创建人的用户名")
 * @SWG\Parameter(parameter="CouponQuery-user_id",name="user_id",in="query",type="integer",description="创建人ID")
 * @SWG\Parameter(parameter="CouponQuery-consumer:username",name="consumer:username",in="query",type="string",description="消费者的用户名")
 * @SWG\Parameter(parameter="CouponQuery-consumer_id",name="consumer_id",in="query",type="integer",description="消费者ID")
 * @SWG\Parameter(parameter="CouponQuery-expired_at",name="ended_at",in="query",type="string",description="截止日期")
 * @SWG\Parameter(parameter="CouponQuery-consumed_at",name="consumed_at",in="query",type="string",description="消费时间")
 * @SWG\Parameter(parameter="CouponQuery-created_at",name="created_at",in="query",type="string",description="创建时间")
 * @SWG\Parameter(parameter="CouponQuery-batch",name="batch",in="query",type="string",description="批次号码")
 * @SWG\Parameter(parameter="CouponQuery-product_type",name="product_type",in="query",type="string",enum={"plan","recharging"},description="适用产品类型")
 *
 * // sortable parameters 排序参数
 * @SWG\Parameter(
 *     parameter="Coupon-sort",
 *     name="sort",
 *     in="query",
 *     type="string",
 *     description="使用方法见 sortable；[created_at,batch,expired_at,consumed_at]",
 * )
 *
 * // Include parameters 关联参数
 * @SWG\Parameter(
 *     parameter="Coupon-include",
 *     name="include",
 *     in="query",
 *     type="string",
 *     description="使用方法见 Include；{user:创建者,consumer:消费者,product:适用商品}",
 * )
 */
class Coupon extends Model
{
    use SearchableTrait, SortableTrait;

    /**
     * @var string 优惠券
     */
    protected $table = 'coupons';

    /**
     * @var array
     */
    public $searchable = [
        'code',
        'type',
        'status',
        'user:username',
        'user_id',
        'consumer_id',
        'consumer:username',
        'expired_at',
        'consumed_at',
        'created_at',
        'batch',
        'product_type',
    ];

    /**
     * @var array
     */
    public $sortable = [
        'created_at',
        'batch',
        'expired_at',
        'consumed_at',
    ];

    /**
     * @var string
     */
    protected $defaultSortCriteria = 'created_at,desc';

    /**
     * @var bool 主键状态
     */
    public $incrementing = false;

    /**
     * @var bool 主键类型
     */
    public $keyType = 'string';

    /**
     * @var bool 主键字段
     */
    public $primaryKey = 'code';

    /**
     * @var array 时间字段
     */
    public $dates = [
        'expired_at',
        'consumed_at',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'value' => 'integer',
    ];

    /**
     * @var array
     */
    public $fillable = [
        'batch',
        'type',
        'value',
        'consumer_id',
        'product_type',
        'product_id',
        'expired_at',
        'remark',
    ];

    /**
     * 创建人
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 消费者
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function consumer()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 商品
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->morphTo();
    }

    /**
     * 是否被使用
     *
     * @return boolean
     */
    public function isUsed()
    {
        return $this->status == CouponStatus::USED;
    }

    /**
     * 是否过期
     *
     * @return boolean
     */
    public function isOverdue()
    {
        return now()->gt($this->expired_at);
    }

    /**
     * 是否适用于该教学版本
     *
     * @return boolean
     */
    public function isApplyPlan($planId)
    {
        return $this->plan_id == $planId;
    }

    /**
     * 计算价格
     *
     * @return integer
     */
    public function calculatePrice($price)
    {
        switch ($this->type) {
            case CouponType::DISCOUNT:
                $price = (int)(ceil($price * $this->value / 100));
                break;
            case CouponType::VOUCHER:
                $price = $price - $this->value >= 0 ? $price - $this->value : 0;
                break;
            case CouponType::AUDITION:
                $price = 0;
                break;
        }
        return $price;
    }


    /**
     * Applies filters.
     *
     * @param Builder $builder query builder
     * @param array $query query parameters to use for search - Input::all() is used by default
     */
    public function scopeFiltered($builder, array $query = [])
    {
        $query = (array)($query ?: array_filter(Input::all()));
        $mode = $this->getQueryMode($query);
        $query = $this->filterNonSearchableParameters($query);
        $constraints = $this->getConstraints($builder, $query);

        $this->applyConstraints($builder, $constraints, $mode);
    }
}
