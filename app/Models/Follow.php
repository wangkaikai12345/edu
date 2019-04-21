<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;

/**
 * @SWG\Definition(
 *      definition="Follow",
 *      type="object",
 *      required={},
 *      description="关注模型",
 *      @SWG\Property(property="id",type="string",readOnly=true),
 *      @SWG\Property(property="user_id",type="integer",description="关注者",readOnly=true),
 *      @SWG\Property(property="follow_id",type="integer",description="被关注者"),
 *      @SWG\Property(property="is_pair",type="bool",default=false,description="是否互相关注",default=0),
 *      @SWG\Property(property="created_at",type="string",format="date-time",description="创建时间",readOnly=true),
 *      @SWG\Property(property="updated_at",type="string",format="date-time",description="更新时间",readOnly=true)
 * )
 * @SWG\Response(
 *      response="FollowPagination",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/Follow")),
 *          @SWG\Property(property="meta",ref="$/definitions/MetaProperty")
 *      )
 * )
 * @SWG\Response(
 *      response="FollowResponse",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/Follow"))
 *      )
 * )
 *
 * // Fillable parameters 表单参数
 * @SWG\Parameter(parameter="FollowForm-follow_id",name="follow_id",required=true,in="formData",type="integer",description="被关注人")
 *
 * // Searchable parameters 搜索参数
 * @SWG\Parameter(parameter="FollowQuery-user_id",name="user_id",in="query",type="integer",description="主动关注者ID")
 * @SWG\Parameter(parameter="FollowQuery-user:username",name="user:username",in="query",type="string",description="主动关注者账户")
 * @SWG\Parameter(parameter="FollowQuery-follow_id",name="follow_id",in="query",type="string",description="被关注者ID")
 * @SWG\Parameter(parameter="FollowQuery-follow:username",name="follow:username",in="query",type="boolean",description="被关注者账户")
 * @SWG\Parameter(parameter="FollowQuery-is_pair",name="is_pair",in="query",type="boolean",description="是否互相关注")
 *
 * // sortable parameters 排序参数
 * @SWG\Parameter(
 *     parameter="Follow-sort",
 *     name="sort",
 *     in="query",
 *     type="string",
 *     description="使用方法见 sortable；[created_at,updated_at]",
 * )
 *
 * // Include parameters 关联参数
 * @SWG\Parameter(
 *     parameter="Follow-include",
 *     name="include",
 *     in="query",
 *     type="string",
 *     description="使用方法见 Include；{user:主动关注者,follow:被关注者}",
 * )
 */
class Follow extends Model
{
    use SortableTrait, SearchableTrait;
//    use Cachable;
    /**
     * @var string 关注表
     */
    protected $table = 'follows';

    /**
     * @var array
     */
    protected $fillable = ['follow_id'];

    /**
     * @var array
     */
    public $sortable = ['created_at', 'updated_at'];

    /**
     * @var array
     */
    public $searchable = ['user_id', 'user:username', 'follow_id', 'follow:username', 'is_pair'];

    /**
     * @var string
     */
    public $defaultSortCretiria = 'created_at,desc';

    /**
     * 主体：主动关注者
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * 客体：被关注人
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function follow()
    {
        return $this->belongsTo(User::class, 'follow_id', 'id');
    }
}
