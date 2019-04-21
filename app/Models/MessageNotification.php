<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;

/**
 * @SWG\Definition(
 *      definition="MessageNotification",
 *      type="object",
 *      required={},
 *      description="私信提醒模型",
 *      @SWG\Property(property="id",type="string",readOnly=true),
 *      @SWG\Property(property="conversation_id",type="integer",description="会话",readOnly=true),
 *      @SWG\Property(property="message_id",type="integer",description="消息",readOnly=true),
 *      @SWG\Property(property="user_id",type="string",description="被提醒用户",readOnly=true),
 *      @SWG\Property(property="is_seen",type="bool",default=false,description="是否已阅读",readOnly=true),
 *      @SWG\Property(property="created_at",type="string",format="date-time",description="创建时间",readOnly=true),
 *      @SWG\Property(property="updated_at",type="string",format="date-time",description="更新时间",readOnly=true)
 * )
 * @SWG\Response(
 *      response="MessageNotificationPagination",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/MessageNotification")),
 *          @SWG\Property(property="meta",ref="$/definitions/MetaProperty")
 *      )
 * )
 * @SWG\Response(
 *      response="MessageNotificationResponse",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/MessageNotification"))
 *      )
 * )
 */
class MessageNotification extends Model
{
    use SortableTrait, SearchableTrait;
//    use Cachable;
    /**
     * @var string 消息通知
     */
    protected $table = 'mc_message_notifications';

    /**
     * @var array
     */
    protected $fillable = ['message_id', 'conversation_id', 'user_id', 'is_seen',];

    /**
     * @var array
     */
    public $searchable = ['user_id'];

    /**
     * @var array
     */
    public $sortable = ['*'];

    /**
     * 接收人
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 会话
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function conversation()
    {
        return $this->belongsTo(Conversation::class, 'conversation_id');
    }

    /**
     * 消息
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function message()
    {
        return $this->belongsTo(Message::class, 'message_id');
    }

    /**
     * 设置已读
     *
     * @return integer
     */
    public function read()
    {
        return $this->update(['is_seen' => 1]);
    }
}
