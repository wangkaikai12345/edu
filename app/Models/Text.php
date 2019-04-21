<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *      definition="Text",
 *      type="object",
 *      required={},
 *      description="图文资源模型",
 *      @SWG\Property(property="id",type="string",readOnly=true),
 *      @SWG\Property(property="body",type="string",default=null,description="图文内容"),
 *      @SWG\Property(property="created_at",type="string",format="date-time",description="创建时间",readOnly=true),
 *      @SWG\Property(property="updated_at",type="string",format="date-time",description="更新时间",readOnly=true)
 * )
 * @SWG\Response(
 *      response="TextPagination",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/Text")),
 *          @SWG\Property(property="meta",ref="$/definitions/MetaProperty")
 *      )
 * )
 * @SWG\Response(
 *      response="TextResponse",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/Text"))
 *      )
 * )
 */
class Text extends Model
{
    /**
     * @var string 图文资源
     */
    protected $table = 'texts';

    /**
     * @var array
     */
    protected $fillable = ['body'];

    /**
     * 任务
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function task()
    {
        return $this->morphOne(Task::class, 'target');
    }
}