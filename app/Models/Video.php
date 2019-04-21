<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *     definition="Video",
 *     type="object",
 *     required={"media_uri","hash","length"},
 *     description="视频资源模型",
 *     @SWG\Property(property="id",type="integer",readOnly=true),
 *     @SWG\Property(property="media_uri",type="string",description="媒体文件地址",pattern="audio/.*"),
 *     @SWG\Property(property="hash",type="string",description="文件HASH（用于区分重复文件）",maxLength=64),
 *     @SWG\Property(property="length",type="integer",description="音频时长，单位：秒",minimum=0,default=0),
 *     @SWG\Property(property="status",type="string",enum={"slicing","sliced","unsliced"},default="unsliced",description="未切片、切片中、已切片"),
 *     @SWG\Property(property="domain",type="string",description="域名"),
 *     @SWG\Property(property="key",type="string",description="原始文件名称"),
 *     @SWG\Property(property="created_at",type="string",format="date-time",description="创建时间",readOnly=true),
 *     @SWG\Property(property="updated_at",type="string",format="date-time",description="更新时间",readOnly=true)
 * )
 */
class Video extends Model
{
    use SoftDeletes;

    /**
     * @var string 视频资源
     */
    protected $table = 'videos';

    /**
     * @var array
     */
    protected $fillable = ['media_uri', 'hash', 'length'];

    /**
     * 活动
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function task()
    {
        return $this->morphOne(Task::class, 'target');
    }
}
