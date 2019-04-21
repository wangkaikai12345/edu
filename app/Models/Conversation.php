<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *      definition="Conversation",
 *      type="object",
 *      required={},
 *      description="会话模型",
 *      @SWG\Property(property="id",type="string",readOnly=true),
 *      @SWG\Property(property="user_id",type="integer",description="会话所属人",readOnly=true),
 *      @SWG\Property(property="another_id",type="integer",description="参与会话的另一个人",readOnly=true),
 *      @SWG\Property(property="last_message_id",type="integer",description="最后一条消息ID",default=null,readOnly=true),
 *      @SWG\Property(property="uuid",type="integer",description="会话创建会产生两个相同会话，该字段标识其为同一会话",readOnly=true),
 *      @SWG\Property(property="created_at",type="string",format="date-time",description="创建时间",readOnly=true),
 *      @SWG\Property(property="updated_at",type="string",format="date-time",description="更新时间",readOnly=true)
 * )
 * @SWG\Response(
 *      response="ConversationPagination",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/Conversation")),
 *          @SWG\Property(property="meta",ref="$/definitions/MetaProperty")
 *      )
 * )
 * @SWG\Response(
 *      response="ConversationResponse",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/Conversation"))
 *      )
 * )
 *
 * // Searchable parameters 搜索参数
 * @SWG\Parameter(parameter="ConversationQuery-user_id",name="user_id",in="query",type="string",description="会话所属人")
 * @SWG\Parameter(parameter="ConversationQuery-user:username",name="user:username",in="query",type="string",description="会话所属人用户名")
 * @SWG\Parameter(parameter="ConversationQuery-another_id",name="another_id",in="query",type="string",description="会话参与人")
 * @SWG\Parameter(parameter="ConversationQuery-another:username",name="another:username",in="query",type="string",description="会话参与人用户名")
 * @SWG\Parameter(parameter="ConversationQuery-uuid",name="uuid",in="query",type="string",description="会话UUID")
 *
 * // sortable parameters 排序参数
 * @SWG\Parameter(
 *     parameter="Conversation-sort",
 *     name="sort",
 *     in="query",
 *     type="string",
 *     description="使用方法见 sortable；[created_at,updated_at]",
 * )
 *
 * // Include parameters 关联参数
 * @SWG\Parameter(
 *     parameter="Conversation-include",
 *     name="include",
 *     in="query",
 *     type="string",
 *     description="使用方法见 Include；{user:会话所属用户,another:会话参与者,messages:会话中的消息,last_message:最后一条消息}",
 * )
 */
class Conversation extends Model
{
    use SortableTrait, SearchableTrait;
//    use Cachable;
    /**
     * @var string 会话表
     */
    protected $table = 'mc_conversations';

    /**
     * @var array 批量赋值
     */
    public $fillable = ['user_id', 'another_id', 'uuid'];

    /**
     * @var array 可搜索字段
     */
    public $searchable = ['user_id', 'user:username', 'another_id', 'another:username', 'uuid'];

    /**
     * @var array 可排序字段
     */
    public $sortable = ['created_at', 'updated_at'];

    protected $with = ['another'];

    /**
     * 会话所属人
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 会话另一个参与的人
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function another()
    {
        return $this->belongsTo(User::class, 'another_id', 'id');
    }

    /**
     * 消息
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
        return $this->hasMany(Message::class, 'conversation_id', 'id');
    }

    /**
     * 最后一条消息
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function last_message()
    {
        return $this->belongsTo(Message::class, 'last_message_id', 'id');
    }

    /**
     * 提醒
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notifications()
    {
        return $this->hasMany(MessageNotification::class, 'conversation_id', 'id');
    }

    /**
     * 未读消息
     *
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function unread(User $user)
    {
        return $this->hasMany(MessageNotification::class, 'conversation_id', 'id')
            ->where('user_id', $user->id)
            ->where('is_seen', 0);
    }

    /**
     * 获取 会话所属人 和 会话参与人 之间的会话
     *
     * @param integer $userId
     * @param integer $anotherId
     * @return Conversation
     */
    public static function getConversationBetween($userId, $anotherId)
    {
        return self::where('user_id', $userId)->where('another_id', $anotherId)->first();
    }
}
