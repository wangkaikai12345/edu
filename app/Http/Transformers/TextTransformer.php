<?php
/**
 * Created by PhpStorm.
 * Category: wangbaolong
 * Date: 2018/4/18
 * Time: 10:22
 */

namespace App\Http\Transformers;

use App\Models\Text;

class TextTransformer extends BaseTransformer
{
    public function transform(Text $media)
    {
        return [
            'id' => $media->id,
            'body' => $media->body,
            'created_at' => $media->created_at ? $media->created_at->toDateTimeString() : null,
            'updated_at' => $media->updated_at ? $media->created_at->toDateTimeString() : null,
        ];
    }
}