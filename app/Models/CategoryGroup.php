<?php

namespace App\Models;

use App\Traits\HashIdTrait;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;

/**
 * @SWG\Definition(
 *     definition="CategoryGroup",
 *     type="object",
 *     required={"code"},
 *     description="分类群组模型",
 *     @SWG\Property(property="id",type="string",readOnly=true),
 *     @SWG\Property(property="depth",type="integer",description="层次深度",default=0,readOnly=true),
 *     @SWG\Property(property="name",type="string",description="组名"),
 *     @SWG\Property(property="created_at",type="string",format="date-time",description="创建时间",readOnly=true),
 *     @SWG\Property(property="updated_at",type="string",format="date-time",description="更新时间",readOnly=true),
 * )
 * @SWG\Response(
 *   response="CategoryGroupPagination",
 *   description="分页响应",
 *   @SWG\Schema(
 *     @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/CategoryGroup")),
 *     @SWG\Property(property="meta",ref="$/definitions/MetaProperty")
 *   )
 * )
 * @SWG\Response(
 *   response="CategoryGroupResponse",
 *   description="数据响应",
 *   @SWG\Schema(
 *     @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/CategoryGroup"))
 *   )
 * )
 *
 * // Fillable parameters 表单参数
 * @SWG\Parameter(parameter="CategoryGroupForm-name",name="name",required=true,in="formData",type="string",description="编号/名称，你可以填写拼音或者英文单词")
 *
 * // Searchable parameters 搜索参数
 * @SWG\Parameter(parameter="CategoryGroupQuery-name",name="name",in="query",type="string",description="编号/名称")
 * @SWG\Parameter(parameter="CategoryGroupQuery-depth",name="name",in="query",type="string",description="分组层次")
 *
 * // sortable parameters 排序参数
 * @SWG\Parameter(
 *     parameter="CategoryGroup-sort",
 *     name="sort",
 *     in="query",
 *     type="string",
 *     description="使用方法见 sortable；[name]",
 * )
 *
 * // Include parameters 关联参数
 * @SWG\Parameter(
 *     parameter="CategoryGroup-include",
 *     name="include",
 *     in="query",
 *     type="string",
 *     description="使用方法见 Include；{categories:群组下的分类}",
 * )
 */
class CategoryGroup extends Model
{
    use SearchableTrait, SortableTrait;
    use HashIdTrait, Cachable;

    /**
     * @var string 分类群组
     */
    protected $table = 'category_groups';

    /**
     * @var array
     */
    public $sortable = ['name', 'title'];

    /**
     * @var array
     */
    public $searchable = ['name', 'title'];

    /**
     * @var array
     */
    protected $fillable = ['name', 'title'];

    /**
     * 分类
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categories()
    {
        return $this->hasMany(Category::class);
    }
}
