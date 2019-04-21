<?php
/**
 * Created by PhpStorm.
 * Category: wangbaolong
 * Date: 2018/4/18
 * Time: 10:22
 */

namespace App\Http\Transformers;

use App\Models\Conversation;

class ConversationTransformer extends BaseTransformer
{
    protected $availableIncludes = ['me', 'him'];

    protected $defaultIncludes = ['last_message'];

    public function transform(Conversation $model)
    {
        return [
            'id' => $model->id,
            'notifications_count' => $model->notifications_count ?? 0,
            'messages_count' => $model->messages_count ?? 0,
            'uuid' => $model->uuid,
            'created_at' => $model->created_at ? $model->created_at->toDateTimeString() : null,
            'updated_at' => $model->updated_at ? $model->updated_at->toDateTimeString() : null,
        ];
    }

    public function includeLastMessage(Conversation $model)
    {
        return $this->setDataOrItem($model->last_message, new MessageTransformer());
    }

    public function includeMe(Conversation $model)
    {
        return $this->setDataOrItem($model->me, new MessageUserTransformer());
    }

    public function includeHim(Conversation $model)
    {
        return $this->setDataOrItem($model->him, new MessageUserTransformer());
    }
}