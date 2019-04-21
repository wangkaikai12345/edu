<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;

/**
 * @SWG\Definition(
 *      definition="Favorite",
 *      type="object",
 *      required={"coin"},
 *      description="收藏/喜爱模型",
 *      @SWG\Property(property="id",type="integer",readOnly=true),
 *      @SWG\Property(property="user_id",type="integer",description="用户",readOnly=true),
 *      @SWG\Property(property="model_id",type="integer",description="模型ID",default=null,readOnly=true),
 *      @SWG\Property(property="model_type",type="string",enum={"course","topic","note"},description="模型类型",default=null,readOnly=true),
 *      @SWG\Property(property="created_at",type="string",format="date-time",description="创建时间",readOnly=true),
 *      @SWG\Property(property="updated_at",type="string",format="date-time",description="更新时间",readOnly=true)
 * )
 * @SWG\Response(
 *      response="FavoritePagination",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/Favorite")),
 *          @SWG\Property(property="meta",ref="$/definitions/MetaProperty")
 *      )
 * )
 *
 * // Fillable parameters 表单参数
 * @SWG\Parameter(parameter="FavoriteForm-model_type",name="model_type",enum={"course","topic","note"},required=true,in="formData",type="string",description="点赞/收藏类型")
 * @SWG\Parameter(parameter="FavoriteForm-model_id",name="model_id",required=true,in="formData",type="integer",description="收藏ID")
 *
 * // Searchable parameters 搜索参数
 * @SWG\Parameter(parameter="FavoriteQuery-model_type",name="model_type",in="query",type="string",enum={"course","topic","note"},description="用户ID")
 * @SWG\Parameter(parameter="FavoriteQuery-user_id",name="user_id",in="query",type="string",description="用户ID")
 * @SWG\Parameter(parameter="FavoriteQuery-user:username",name="user:username",in="query",type="string",description="用户名称")
 *
 * // sortable parameters 排序参数
 * @SWG\Parameter(
 *     parameter="Favorite-sort",
 *     name="sort",
 *     in="query",
 *     type="string",
 *     description="使用方法见 sortable；[created_at]",
 * )
 *
 * // Include parameters 关联参数
 * @SWG\Parameter(
 *     parameter="Favorite-include",
 *     name="include",
 *     in="query",
 *     type="string",
 *     description="使用方法见 Include；{model:收藏模型,user:所有者}",
 * )
 */
class Favorite extends Model
{
    use SearchableTrait, SortableTrait;
//    use Cachable;
    /**
     * @var string 收藏/喜爱表
     */
    protected $table = 'favorites';

    /**
     * @var array
     */
    public $sortable = [
        'created_at',
    ];

    /**
     * @var string
     */
    protected $defaultSortCriteria = 'created_at,desc';

    /**
     * @var array
     */
    public $searchable = [
        'user_id',
        'user:username',
        'model_type',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];

    /**
     * @var array
     */
    public $fillable = [
        'model_type',
        'model_id',
    ];

    /**
     * 课程/笔记/话题
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function model()
    {
        return $this->morphTo('model', 'model_type', 'model_id');
    }

    /**
     * 话题
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function note()
    {
        return $this->morphTo('model', 'model_type', 'model_id');
    }

    /**
     * 话题
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function topic()
    {
        return $this->morphTo('model', 'model_type', 'model_id');
    }

    /**
     * 课程
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function course()
    {
        return $this->morphTo('model', 'model_type', 'model_id');
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
}
