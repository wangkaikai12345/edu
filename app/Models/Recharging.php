<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;

/**
 * @SWG\Definition(
 *      definition="Recharging",
 *      type="object",
 *      required={},
 *      description="充值模型",
 *      @SWG\Property(property="id",type="integer",readOnly=true),
 *      @SWG\Property(property="title",type="string",maxLength=36,description="标题",readOnly=true),
 *      @SWG\Property(property="price",type="string",description="价格，单位：分",readOnly=true),
 *      @SWG\Property(property="origin_price",type="integer",description="原价格，单位：分"),
 *      @SWG\Property(property="coin",type="integer",description="等值虚拟币个数"),
 *      @SWG\Property(property="extra_coin",type="integer",description="额外赠送虚拟币个数"),
 *      @SWG\Property(property="income",type="integer",description="本商品总收入"),
 *      @SWG\Property(property="bought_count",type="integer",description="本商品总购买个数"),
 *      @SWG\Property(property="status",type="string",enum={"draft","published","closed"},description="状态：未发布、已发布、已关闭"),
 *      @SWG\Property(property="user_id",type="integer",description="创建人"),
 *      @SWG\Property(property="created_at",type="string",format="date-time",description="创建时间",readOnly=true),
 *      @SWG\Property(property="updated_at",type="string",format="date-time",description="更新时间",readOnly=true),
 * )
 * @SWG\Response(
 *      response="RechargingPagination",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/Recharging")),
 *          @SWG\Property(property="meta",ref="$/definitions/MetaProperty")
 *      )
 * )
 * @SWG\Response(
 *      response="RechargingResponse",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/Recharging"))
 *      )
 * )
 *
 * // Fillable parameters 表单参数
 * @SWG\Parameter(parameter="RechargingForm-title",name="title",required=true,in="formData",type="integer",description="充值")
 * @SWG\Parameter(parameter="RechargingForm-price",name="price",required=true,in="formData",type="integer",description="价格")
 * @SWG\Parameter(parameter="RechargingForm-origin_price",name="origin_price",required=true,in="formData",type="integer",description="原价格")
 * @SWG\Parameter(parameter="RechargingForm-coin",name="coin",required=true,in="formData",type="integer",description="等值虚拟币个数")
 * @SWG\Parameter(parameter="RechargingForm-extra_coin",name="extra_coin",required=true,in="formData",type="integer",description="额外赠送虚拟币个数")
 * @SWG\Parameter(parameter="RechargingForm-status",name="status",required=true,in="formData",type="string",enum={"draft","published","closed"},description="状态：未发布、已发布、已关闭")
 *
 * // Searchable parameters 搜索参数
 * @SWG\Parameter(parameter="RechargingQuery-title",name="title",in="query",type="string",description="任务ID")
 * @SWG\Parameter(parameter="RechargingQuery-price",name="price",in="query",type="integer",description="当前价格")
 * @SWG\Parameter(parameter="RechargingQuery-origin_price",name="origin_price",in="query",type="integer",description="原价格")
 * @SWG\Parameter(parameter="RechargingQuery-coin",name="coin",in="query",type="integer",description="虚拟币个数")
 * @SWG\Parameter(parameter="RechargingQuery-extra_coin",name="extra_coin",in="query",type="integer",description="额外赠送虚拟币个数")
 * @SWG\Parameter(parameter="RechargingQuery-income",name="income",in="query",type="integer",description="总收入")
 * @SWG\Parameter(parameter="RechargingQuery-bought_count",name="bought_count",in="query",type="integer",description="购买次数")
 * @SWG\Parameter(parameter="RechargingQuery-status",name="status",in="query",type="string",enum={"draft","published","closed"},description="状态")
 * @SWG\Parameter(parameter="RechargingQuery-user_id",name="user_id",in="query",type="integer",description="用户ID")
 * @SWG\Parameter(parameter="RechargingQuery-user:username",name="user:username",in="query",type="integer",description="创建人")
 *
 * // sortable parameters 排序参数
 * @SWG\Parameter(
 *     parameter="Recharging-sort",
 *     name="sort",
 *     in="query",
 *     type="string",
 *     description="使用方法见 sortable；[price,origin_price,coin,extra_coin,income,bought_count]",
 * )
 *
 * // Include parameters 关联参数
 * @SWG\Parameter(
 *     parameter="Recharging-include",
 *     name="include",
 *     in="query",
 *     type="string",
 *     description="使用方法见 Include；{task:附属任务,test:附属考试,question:附属题目,user:答题人}",
 * )
 */
class Recharging extends Model
{
    use SoftDeletes, SortableTrait, SearchableTrait;

    /**
     * @var string 充值
     */
    protected $table = 'rechargings';

    /**
     * @var array
     */
    public $sortable = [
        'price',
        'origin_price',
        'extra_coin',
        'coin',
        'income',
        'bought_count',
    ];

    /**
     * @var array
     */
    public $searchable = [
        'title',
        'price',
        'origin_price',
        'extra_coin',
        'coin',
        'income',
        'bought_count',
        'status',
        'user_id',
        'user:username',
    ];
    /**
     * @var string sortable 默认排序
     */
    public $defaultSortCriteria = 'price,desc';

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'price',
        'origin_price',
        'extra_coin',
        'coin',
    ];

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

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
     * 订单
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function orders()
    {
        return $this->morphMany(Order::class, 'product');
    }
}
