<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;

/**
 * @SWG\Definition(
 *      definition="Slide",
 *      type="object",
 *      required={},
 *      description="轮播图模型",
 *      @SWG\Property(property="id",type="string",readOnly=true),
 *      @SWG\Property(property="title",type="string",description="标题"),
 *      @SWG\Property(property="seq",type="integer",description="排序"),
 *      @SWG\Property(property="image",type="string",description="图片"),
 *      @SWG\Property(property="link",type="string",description="超链接"),
 *      @SWG\Property(property="description",type="string",description="描述信息"),
 *      @SWG\Property(property="user_id",type="integer",description="创建人",readOnly=true),
 *      @SWG\Property(property="created_at",type="string",format="date-time",description="创建时间",readOnly=true),
 *      @SWG\Property(property="updated_at",type="string",format="date-time",description="更新时间",readOnly=true)
 * )
 * @SWG\Response(
 *      response="SlidePagination",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/Slide")),
 *          @SWG\Property(property="meta",ref="$/definitions/MetaProperty")
 *      )
 * )
 * @SWG\Response(
 *      response="SlideResponse",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/Slide"))
 *      )
 * )
 */
class Slide extends Model
{
    use SoftDeletes, SortableTrait, SearchableTrait;
    use Cachable;
    /**
     * @var string 轮播图
     */
    protected $table = 'slides';

    /**
     * @var array
     */
    protected $sortable = ['*'];

    /**
     * @var array
     */
    protected $searchable = ['seq'];

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @var array
     */
    protected $fillable = ['title', 'image', 'link', 'seq', 'description', 'user_id',];

    /**
     * @var array
     */
    public static $baseFields = ['id', 'title', 'image', 'link'];

    /**
     * 创建人
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
