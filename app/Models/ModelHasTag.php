<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Sortable\SortableTrait;

/**
 * @SWG\Definition(
 *      definition="ModelHasTag",
 *      type="object",
 *      required={},
 *      description="标签所属者模型",
 *      @SWG\Property(property="tag_id",type="string",description="标签",readOnly=true),
 *      @SWG\Property(property="model_id",type="integer",description="模型ID",readOnly=true),
 *      @SWG\Property(property="model_type",type="string",description="模型",readOnly=true),
 *      @SWG\Property(property="created_at",type="string",format="date-time",description="创建时间",readOnly=true),
 *      @SWG\Property(property="updated_at",type="string",format="date-time",description="更新时间",readOnly=true)
 * )
 * @SWG\Response(
 *      response="ModelHasTagPagination",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/ModelHasTag")),
 *          @SWG\Property(property="meta",ref="$/definitions/MetaProperty")
 *      )
 * )
 * @SWG\Response(
 *      response="ModelHasTagResponse",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/ModelHasTag"))
 *      )
 * )
 */
class ModelHasTag extends Model
{
    use SortableTrait;

    /**
     * @var string 标签所有者
     */
    protected $table = 'model_has_tags';

    /**
     * @var array
     */
    public $sortable = ['*'];

    /**
     * @var array
     */
    protected $fillable = ['model_id', 'model_type', 'tag_id'];

    /**
     * 所有者
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function model()
    {
        return $this->morphTo();
    }

    /**
     * 标签
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }
}
