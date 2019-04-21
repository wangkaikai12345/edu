<?php
/**
 * Created by PhpStorm.
 * Category: wangbaolong
 * Date: 2018/4/18
 * Time: 10:22
 */

namespace App\Http\Transformers;

use App\Enums\SettingType;
use Facades\App\Models\Setting;
use App\Models\Video;
use League\Fractal\TransformerAbstract;

class VideoTransformer extends TransformerAbstract
{
    /**
     * @var string 域名
     */
    public $domain;

    public function __construct()
    {
        $this->domain = Setting::namespace(SettingType::QINIU)['slice_domain'];
    }

    public function transform(Video $media)
    {
        return [
            'id' => $media->id,
            'media_uri' => $this->domain . '/' . $media->media_uri,
            'domain' => $this->domain,
            'key' => $media->media_uri,
            'length' => $media->length,
            'status' => $media->status,
            'created_at' => $media->created_at ? $media->created_at->toDateTimeString() : null,
            'updated_at' => $media->updated_at ? $media->created_at->toDateTimeString() : null,
        ];
    }
}