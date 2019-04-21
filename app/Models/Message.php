<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Input;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;

/**
 * @SWG\Definition(
 *      definition="Message",
 *      type="object",
 *      required={},
 *      description="私信模型",
 *      @SWG\Property(property="id",type="string",readOnly=true),
 *      @SWG\Property(property="conversation_id",type="integer",default=0,description="会话"),
 *      @SWG\Property(property="sender_id",type="integer",description="发信人",readOnly=true),
 *      @SWG\Property(property="recipient_id",type="integer",description="收信人",readOnly=true),
 *      @SWG\Property(property="body",type="string",default=0,description="私信内容"),
 *      @SWG\Property(property="type",type="string",default="text",description="类型：文本"),
 *      @SWG\Property(property="uuid",type="string",default=0,description="UUID"),
 *      @SWG\Property(property="created_at",type="string",format="date-time",description="创建时间",readOnly=true),
 *      @SWG\Property(property="updated_at",type="string",format="date-time",description="更新时间",readOnly=true)
 * )
 * @SWG\Response(
 *      response="MessagePagination",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/Message")),
 *          @SWG\Property(property="meta",ref="$/definitions/MetaProperty")
 *      )
 * )
 * @SWG\Response(
 *      response="MessageResponse",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/Message"))
 *      )
 * )
 *
 * // Searchable parameters 搜索参数
 * @SWG\Parameter(parameter="MessageQuery-sender_id",name="sender_id",in="query",type="string",description="发信人")
 * @SWG\Parameter(parameter="MessageQuery-sender:username",name="sender:username",in="query",type="string",description="发信人用户名")
 * @SWG\Parameter(parameter="MessageQuery-recipient_id",name="recipient_id",in="query",type="string",description="收信人")
 * @SWG\Parameter(parameter="MessageQuery-recipient:username",name="recipient:username",in="query",type="string",description="收信人用户名")
 * @SWG\Parameter(parameter="MessageQuery-created_at",name="created_at",in="query",type="string",description="创建时间")
 *
 * // sortable parameters 排序参数
 * @SWG\Parameter(
 *     parameter="Message-sort",
 *     name="sort",
 *     in="query",
 *     type="string",
 *     description="使用方法见 sortable；[created_at]",
 * )
 *
 * // Include parameters 关联参数
 * @SWG\Parameter(
 *     parameter="Message-include",
 *     name="include",
 *     in="query",
 *     type="string",
 *     description="使用方法见 Include；{sender:发信人,recipient:接收人,conversation:所属会话}",
 * )
 */
class Message extends Model
{
    use SortableTrait, SearchableTrait;
//    use Cachable;
    /**
     * @var string 消息表
     */
    protected $table = 'mc_messages';

    /**
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @var array 可搜索字段
     */
    public $searchable = [
        'sender_id',
        'recipient_id',
        'sender:username',
        'recipient:username',
        'created_at'
    ];

    protected $with = ['sender', 'recipient'];

    /**
     * @var array
     */
    public $sortable = ['created_at'];

    /**
     * 发信人
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id')->select(['id', 'username', 'avatar']);
    }

    /**
     * 收信人
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id')->select(['id', 'username', 'avatar']);
    }

    /**
     * 会话
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    /**
     * Applies filters.
     *
     * @param  $builder
     * @param array $query query parameters to use for search - Input::all() is used by default
     */
    public function scopeFiltered($builder, array $query = [])
    {
        $query = (array)($query ?: array_filter(Input::all()));
        $mode = $this->getQueryMode($query);
        $query = $this->filterNonSearchableParameters($query);
        $constraints = $this->getConstraints($builder, $query);

        $this->applyConstraints($builder, $constraints, $mode);
    }
}
