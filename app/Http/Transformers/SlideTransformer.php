<?php
/**
 * Created by PhpStorm.
 * User: wangbaolong
 * Date: 2018/4/18
 * Time: 10:22
 */

namespace App\Http\Transformers;

use App\Enums\SettingType;
use Facades\App\Models\Setting;
use App\Models\Slide;

class SlideTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected $availableIncludes = ['user'];

    /**
     * @var string 域名
     */
    public $domain;

    public function __construct()
    {
        $this->domain = Setting::namespace(SettingType::QINIU)['public_domain'];
    }

    public function transform(Slide $model)
    {
        return [
            'id' => $model->id,
            'title' => $model->title,
            'seq' => $model->seq ?? 0,
            'image' => $model->image ? $this->domain . '/' . $model->image : null,
            'link' => $model->link,
            'description' => $model->description,
            'created_at' => $model->created_at ? $model->created_at->toDateTimeString() : null,
            'updated_at' => $model->updated_at ? $model->updated_at->toDateTimeString() : null
        ];
    }

    /**
     * 创建人
     */
    public function includeUser(Slide $model)
    {
        return $this->setDataOrItem($model->user, new MessageUserTransformer());
    }
}