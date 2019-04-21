<?php
/**
 * Created by PhpStorm.
 * Category: wangbaolong
 * Date: 2018/4/18
 * Time: 10:22
 */

namespace App\Http\Transformers;

class MessageTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected $defaultIncludes = ['sender', 'recipient'];

    public function transform($model)
    {
        return [
            'id' => $model->id,
            'body' => $model->body,
            'conversation_id' => $model->conversation_id,
            'type' => $model->type,
            'uuid' => $model->uuid,
            'created_at' => $model->created_at ? $model->created_at->toDateTimeString() : null,
            'updated_at' => $model->updated_at ? $model->updated_at->toDateTimeString() : null,
        ];
    }

    /**
     * 发消息人
     */
    public function includeSender($model)
    {
        return $this->setDataOrItem($model->sender, new MessageUserTransformer());
    }

    /**
     * 发消息人
     */
    public function includeRecipient($model)
    {
        return $this->setDataOrItem($model->recipient, new MessageUserTransformer());
    }
}