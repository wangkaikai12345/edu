<?php

namespace App\Models;

use App\Traits\HashIdTrait;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;

/**
 * @SWG\Definition(
 *     definition="Category",
 *     type="object",
 *     required={"coin"},
 *     description="分类模型",
 *     @SWG\Property(property="id",type="integer",readOnly=true),
 *     @SWG\Property(property="name",type="string",description="类名",readOnly=true),
 *     @SWG\Property(property="icon",type="string",description="图标",default=null),
 *     @SWG\Property(property="seq",type="integer",description="降序排序序号",default=0),
 *     @SWG\Property(property="parent_id",type="integer",description="父级（允许无限极）",default=0,readOnly=true),
 *     @SWG\Property(property="category_group_id",type="integer",description="分类群组",readOnly=true),
 *     @SWG\Property(property="created_at",type="string",format="date-time",description="创建时间",readOnly=true),
 *     @SWG\Property(property="updated_at",type="string",format="date-time",description="更新时间",readOnly=true)
 * )
 * @SWG\Response(
 *   response="CategoryPagination",
 *   description="",
 *   @SWG\Schema(
 *     @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/Category")),
 *     @SWG\Property(property="meta",ref="$/definitions/MetaProperty")
 *   )
 * )
 * @SWG\Response(
 *   response="CategoryResponse",
 *   description="",
 *   @SWG\Schema(
 *     @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/Category")),
 *   )
 * )
 * 
 * // Fillable parameters 表单参数
 * @SWG\Parameter(parameter="CategoryForm-name",name="name",required=true,in="formData",type="string",minLength=1,maxLength=191,description="名称")
 * @SWG\Parameter(parameter="CategoryForm-seq",name="seq",in="formData",type="integer",description="降序排序序号",default=0)
 *
 * // Searchable parameters 搜索参数
 * @SWG\Parameter(parameter="CategoryQuery-name",name="name",in="query",type="string",description="名称")
 * @SWG\Parameter(parameter="CategoryQuery-parent_id",name="parent_id",in="query",type="integer",description="父级ID")
 * @SWG\Parameter(parameter="CategoryQuery-parent:name",name="parent:name",in="query",type="string",description="父级名称")
 *
 * // sortable parameters 排序参数
 * @SWG\Parameter(
 *     parameter="Category-sort",
 *     name="sort",
 *     in="query",
 *     type="string",
 *     description="使用方法见 sortable；[seq,parent_id]",
 * )
 *
 * // Include parameters 关联参数
 * @SWG\Parameter(
 *     parameter="Category-include",
 *     name="include",
 *     in="query",
 *     type="string",
 *     description="使用方法见 Include；{children:子集}",
 * )
 */
class Category extends Model
{
    use SoftDeletes, SearchableTrait, SortableTrait, HashIdTrait;
    use Cachable;

    /**
     * @var string 分类
     */
    protected $table = 'categories';

    /**
     * @var array
     */
    public $searchable = ['name', 'parent_id', 'parent:name'];

    /**
     * @var array
     */
    public $sortable = ['seq', 'parent_id'];

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @var array
     */
    protected $fillable = ['name', 'seq',];

    /**
     * @var array
     */
    public static $baseFields = ['id', 'name', 'parent_id'];

    /**
     * 所属群组
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(CategoryGroup::class);
    }

    /**
     * 子类
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }

    /**
     * 获取父级
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id', 'id');
    }
}
