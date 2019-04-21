<?php
/**
 * Created by PhpStorm.
 * User: wangbaolong
 * Date: 2018/4/18
 * Time: 10:22
 */

namespace App\Http\Transformers;

use Illuminate\Notifications\DatabaseNotification;

class NotificationTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected $availableIncludes = [];

    /**
     * @var array
     */
    protected $defaultIncludes = ['notifiable'];

    public function transform(DatabaseNotification $model)
    {
        return [
            'id' => $model->id,
            'type' => $model->type,
            'data' => $model->data,
            'read_at'=> $model->read_at ? $model->read_at->toDateTimeString() : null,
            'created_at' => $model->created_at ? $model->created_at->toDateTimeString() : null,
            'updated_at' => $model->updated_at ? $model->updated_at->toDateTimeString() : null,
        ];
    }

    /**
     * 多态关联（默认为 User）
     */
    public function includeNotifiable(DatabaseNotification $model)
    {
        return $this->setDataOrItem($model->notifiable, new UserTransformer());
    }
}