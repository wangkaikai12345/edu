<?php
/**
 * Created by PhpStorm.
 * User: wangbaolong
 * Date: 2018/4/18
 * Time: 10:22
 */

namespace App\Http\Transformers;

use App\Models\Profile;
use League\Fractal\TransformerAbstract;

class ProfileTransformer extends TransformerAbstract
{
    public function transform(Profile $model)
    {
        return [
            'user_id' => $model->user_id,
            'title' => $model->title,
            'name' => $model->name,
            'idcard' => $model->idcard,
            'gender' => $model->gender,
            'birthday' => $model->birthday,
            'city' => $model->city,
            'about' => $model->about,
            'company' => $model->company,
            'job' => $model->job,
            'school' => $model->school,
            'major' => $model->major,
            'qq' => $model->qq,
            'weibo' => $model->weibo,
            'weixin' => $model->weixin,
            'is_qq_public' => (boolean)$model->is_qq_public,
            'is_weixin_public' => (boolean)$model->is_weixin_public,
            'is_weibo_public' => (boolean)$model->is_weibo_public,
            'site' => $model->site,
            'created_at' => $model->created_at ? $model->created_at->toDateTimeString() : null,
            'updated_at' => $model->updated_at ? $model->updated_at->toDateTimeString() : null,
        ];
    }
}