<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *     definition="Image",
 *     type="object",
 *     required={"media_uri","hash","length"},
 *     description="图片资源模型",
 *     @SWG\Property(property="id",type="integer",readOnly=true),
 *     @SWG\Property(property="media_uri",type="string",description="媒体文件地址",pattern="audio/.*"),
 *     @SWG\Property(property="hash",type="string",description="文件HASH（用于区分重复文件）",maxLength=64),
 *     @SWG\Property(property="length",type="integer",description="图片大小，单位：byte",minimum=0,default=0),
 *     @SWG\Property(property="domain",type="string",description="域名"),
 *     @SWG\Property(property="key",type="string",description="原始文件名称"),
 *     @SWG\Property(property="created_at",type="string",format="date-time",description="创建时间",readOnly=true),
 *     @SWG\Property(property="updated_at",type="string",format="date-time",description="更新时间",readOnly=true)
 * )
 */
class Image extends Model
{
    /**
     * @var string 图片资源表
     */
    protected $table = 'images';

    /**
     * @var array
     */
    protected $fillable = ['media_uri', 'hash'];
}
