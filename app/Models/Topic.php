<?php

namespace App\Models;

use Carbon\Carbon;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;

/**
 * @SWG\Definition(
 *      definition="Topic",
 *      type="object",
 *      required={},
 *      description="图文资源模型",
 *      @SWG\Property(property="id",type="string",readOnly=true),
 *      @SWG\Property(property="type",type="string",enum={"discussion","question"},description="讨论话题、问答话题",readOnly=true),
 *      @SWG\Property(property="title",type="string",description="标题"),
 *      @SWG\Property(property="content",type="string",description="内容"),
 *      @SWG\Property(property="is_stick",type="bool",default=false,description="是否置顶",readOnly=true),
 *      @SWG\Property(property="is_elite",type="bool",default=false,description="是否精华",readOnly=true),
 *      @SWG\Property(property="is_audited",type="boolean",default=false,description="是否已审核",readOnly=true),
 *      @SWG\Property(property="user_id",type="integer",description="创建人",readOnly=true),
 *      @SWG\Property(property="course_id",type="integer",description="课程",readOnly=true),
 *      @SWG\Property(property="plan_id",type="integer",description="版本",readOnly=true),
 *      @SWG\Property(property="task_id",type="integer",description="任务",readOnly=true),
 *      @SWG\Property(property="replies_count",type="integer",description="评论数",readOnly=true),
 *      @SWG\Property(property="hit_count",type="integer",description="点击数",readOnly=true),
 *      @SWG\Property(property="latest_reply_user_id",type="integer",description="最后回复的人",readOnly=true),
 *      @SWG\Property(property="status",type="string",enum={"qualified","violation"},default="qualified",description="状态：合格、违规",readOnly=true),
 *      @SWG\Property(property="created_at",type="string",format="date-time",description="创建时间",readOnly=true),
 *      @SWG\Property(property="updated_at",type="string",format="date-time",description="更新时间",readOnly=true)
 * )
 * @SWG\Response(
 *      response="TopicPagination",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/Topic")),
 *          @SWG\Property(property="meta",ref="$/definitions/MetaProperty")
 *      )
 * )
 * @SWG\Response(
 *      response="TopicResponse",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/Topic"))
 *      )
 * )
 *
 * // Fillable parameters 表单参数
 * @SWG\Parameter(parameter="TopicForm-type",name="type",required=true,in="formData",type="string",enum={"discussion","question"},description="讨论话题、问答话题")
 * @SWG\Parameter(parameter="TopicForm-title",name="title",required=true,in="formData",type="string",description="话题")
 * @SWG\Parameter(parameter="TopicForm-content",name="content",required=true,in="formData",type="string",description="话题内容")
 * @SWG\Parameter(parameter="TopicForm-status",name="status",required=true,in="formData",type="string",enum={"qualified","violation"},default="qualified",description="状态：合格、违规")
 * @SWG\Parameter(parameter="TopicForm-is_elite",name="is_elite",required=true,in="formData",type="boolean",description="是否加精")
 * @SWG\Parameter(parameter="TopicForm-is_stick",name="is_stick",required=true,in="formData",type="boolean",description="是否置顶")
 * @SWG\Parameter(parameter="TopicForm-is_audited",name="content",required=true,in="formData",type="boolean",description="是否审核")
 *
 * // Searchable parameters 搜索参数
 * @SWG\Parameter(parameter="TopicQuery-type",name="type",in="query",type="string",enum={"single","multiple","judge"},description="类型：单选/多选/判断")
 * @SWG\Parameter(parameter="TopicQuery-title",name="title",in="query",type="string",description="话题")
 * @SWG\Parameter(parameter="TopicQuery-is_stick",name="is_stick",in="query",type="boolean",description="是否置顶")
 * @SWG\Parameter(parameter="TopicQuery-is_elite",name="is_elite",in="query",type="boolean",description="是否精华")
 * @SWG\Parameter(parameter="TopicQuery-is_audited",name="is_audited",in="query",type="boolean",description="是否审核")
 * @SWG\Parameter(parameter="TopicQuery-user_id",name="user_id",in="query",type="integer",description="作者ID")
 * @SWG\Parameter(parameter="TopicQuery-user:username",name="user:username",in="query",type="string",description="作者账户")
 * @SWG\Parameter(parameter="TopicQuery-course_id",name="course_id",in="query",type="integer",description="课程ID")
 * @SWG\Parameter(parameter="TopicQuery-course:title",name="course:title",in="query",type="string",description="课程标题")
 * @SWG\Parameter(parameter="TopicQuery-plan_id",name="plan_id",in="query",type="integer",description="版本ID")
 * @SWG\Parameter(parameter="TopicQuery-plan:title",name="plan:title",in="query",type="string",description="版本标题")
 * @SWG\Parameter(parameter="TopicQuery-task_id",name="chapter_id",in="query",type="integer",description="任务ID")
 * @SWG\Parameter(parameter="TopicQuery-task:title",name="chapter:title",in="query",type="string",description="任务标题")
 * @SWG\Parameter(parameter="TopicQuery-replies_count",name="chapter:title",in="query",type="string",description="回复量")
 * @SWG\Parameter(parameter="TopicQuery-hit_count",name="chapter:title",in="query",type="string",description="点击量")
 * @SWG\Parameter(parameter="TopicQuery-latest_replied_at",name="latest_replied_at",in="query",type="string",format="date-time",description="最新回复时间")
 * @SWG\Parameter(parameter="TopicQuery-latest_replier_id",name="latest_replier_id",in="query",type="string",description="最新回复人ID")
 * @SWG\Parameter(parameter="TopicQuery-latest_replier:username",name="latest_replier:username",in="query",type="string",description="最新回复人账户")
 * @SWG\Parameter(parameter="TopicQuery-status",name="status",in="query",type="string",enum={"qualified","violation"},description="合格/违规")
 *
 * // sortable parameters 排序参数
 * @SWG\Parameter(
 *     parameter="Topic-sort",
 *     name="sort",
 *     in="query",
 *     type="string",
 *     description="使用方法见 sortable；[replies_count,hit_count]",
 * )
 *
 * // Include parameters 关联参数
 * @SWG\Parameter(
 *     parameter="Topic-include",
 *     name="include",
 *     in="query",
 *     type="string",
 *     description="使用方法见 Include；{course:附属课程,plan:附属版本,task:附属任务,user:作者,topic:话题,latest_replier:最新回复人}",
 * )
 */
class Topic extends Model
{
    use SearchableTrait, SortableTrait;

//    use Cachable;

    /**
     * @var string 话题
     */
    protected $table = 'topics';

    /**
     * @var array
     */
    protected $sortable = [
        'created_at',
        'replies_count',
        'hit_count',
        'latest_replied_at',
    ];

    /**
     * @var array
     */
    public $searchable = [
        'type',
        'title',
        'is_stick',
        'is_elite',
        'is_audited',
        'user_id',
        'user:username',
        'course_id',
        'course:title',
        'plan_id',
        'plan:title',
        'task_id',
        'task:title',
        'replies_count',
        'hit_count',
        'latest_replied_at',
        'latest_replier_id',
        'latest_replier:username',
    ];

    /**
     * @var array
     */
    protected $fillable = ['task_id', 'type', 'title', 'content',];

    /**
     * @var string 默认排序
     */
    public $defaultSortCriteria = 'created_at,desc';

    /**
     * @var array
     */
    public static $baseFields = [
        'id',
        'title',
        'created_at',
        'type',
        'is_stick',
        'is_elite',
        'created_at'
    ];

    /**
     * 回复
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    /**
     * 作者
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 最后回复者
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function latest_replier()
    {
        return $this->belongsTo(User::class, 'latest_replier_id', 'id');
    }

    /**
     * 课程
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * 版本
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * 任务
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * 指定时间段内的新增话题
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Carbon\Carbon $start
     * @param \Carbon\Carbon $end
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNew($query, Carbon $start, Carbon $end)
    {
        return $query->whereBetween('created_at', [$start, $end]);
    }
}
