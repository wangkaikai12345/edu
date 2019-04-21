<?php
/**
 * Created by PhpStorm.
 * Category: wangbaolong
 * Date: 2018/4/18
 * Time: 10:22
 */

namespace App\Http\Transformers;

use App\Models\Log;
use Zhuzhichao\IpLocationZh\Ip;

class LogTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected $availableIncludes = ['user'];

    /**
     * @var array
     */
    protected $defaultIncludes = [];

    public function transform(Log $model)
    {
        return [
            'id' => $model->id,
            'user_id' => $model->user_id,
            'ip' => $model->ip,
            'referer' => $model->referer,
            'device' => $model->device,
            'browser' => $model->browser,
            'browser_version' => $model->browser_version,
            'platform' => $model->platform,
            'platform_version' => $model->platform_version,
            'is_mobile' => $model->is_mobile,
            'request_time' => $model->request_time ? $model->request_time->toDateTimeString() : null,
            'area' => $model->area,
            'created_at' => $model->created_at ? $model->created_at->toDateTimeString() : null,
            'updated_at' => $model->updated_at ? $model->updated_at->toDateTimeString() : null,
        ];

    }

    /**
     * 用户
     */
    public function includeUser(Log $model)
    {
        return $this->setDataOrItem($model->user, new UserTransformer());
    }
}