<?php

namespace App\Models;

use App\Traits\HashIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jedrzej\Sortable\SortableTrait;

/**
 * @SWG\Definition(
 *      definition="TagGroup",
 *      type="object",
 *      required={},
 *      description="标签群组模型",
 *      @SWG\Property(property="id",type="integer",readOnly=true),
 *      @SWG\Property(property="name",type="string",description="名称"),
 *      @SWG\Property(property="description",type="string",description="描述"),
 *      @SWG\Property(property="tags_count",type="integer",description="标签个数",readOnly=true),
 *      @SWG\Property(property="created_at",type="string",format="date-time",description="创建时间",readOnly=true),
 *      @SWG\Property(property="updated_at",type="string",format="date-time",description="更新时间",readOnly=true)
 * )
 * @SWG\Response(
 *      response="TagGroupPagination",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/TagGroup")),
 *          @SWG\Property(property="meta",ref="$/definitions/MetaProperty")
 *      )
 * )
 * @SWG\Response(
 *      response="TagGroupResponse",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/TagGroup"))
 *      )
 * )
 */
class TagGroup extends Model
{
    use SortableTrait, HashIdTrait;

    /**
     * @var string 标签群组
     */
    protected $table = 'tag_groups';

    /**
     * @var array
     */
    public $sortable = ['*'];

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @var array
     */
    protected $fillable = ['name', 'description'];

    /**
     * 标签
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tags()
    {
        return $this->hasMany(Tag::class);
    }
}
