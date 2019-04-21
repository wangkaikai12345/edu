<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jedrzej\Sortable\SortableTrait;

/**
 * @SWG\Definition(
 *      definition="Tag",
 *      type="object",
 *      required={},
 *      description="标签模型",
 *      @SWG\Property(property="id",type="integer",readOnly=true),
 *      @SWG\Property(property="name",type="string",description="名称"),
 *      @SWG\Property(property="group_id",type="string",description="群组"),
 *      @SWG\Property(property="created_at",type="string",format="date-time",description="创建时间",readOnly=true),
 *      @SWG\Property(property="updated_at",type="string",format="date-time",description="更新时间",readOnly=true)
 * )
 * @SWG\Response(
 *      response="TagPagination",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/Tag")),
 *          @SWG\Property(property="meta",ref="$/definitions/MetaProperty")
 *      )
 * )
 * @SWG\Response(
 *      response="TagResponse",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/Tag"))
 *      )
 * )
 */
class Tag extends Model
{
    use SoftDeletes, SortableTrait;

    /**
     * @var string 标签
     */
    protected $table = 'tags';

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
    protected $fillable = ['name'];

    /**
     * 群组
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(TagGroup::class);
    }
}
