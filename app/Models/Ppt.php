<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *     definition="Ppt",
 *     type="object",
 *     required={"media_uri","hash","length"},
 *     description="PPT资源模型",
 *     @SWG\Property(property="id",type="integer",readOnly=true),
 *     @SWG\Property(property="media_uri",type="string",description="媒体文件地址",pattern="audio/.*"),
 *     @SWG\Property(property="hash",type="string",description="文件HASH（用于区分重复文件）",maxLength=64),
 *     @SWG\Property(property="length",type="integer",description="PPT长度，单位：页",minimum=0,default=0),
 *     @SWG\Property(property="domain",type="string",description="域名"),
 *     @SWG\Property(property="key",type="string",description="原始文件名称"),
 *     @SWG\Property(property="created_at",type="string",format="date-time",description="创建时间",readOnly=true),
 *     @SWG\Property(property="updated_at",type="string",format="date-time",description="更新时间",readOnly=true)
 * )
 */
class Ppt extends Model
{
    /**
     * @var string PPT资源表
     */
    protected $table = 'ppts';

    /**
     * @var array
     */
    protected $fillable = ['media_uri', 'length', 'hash'];

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