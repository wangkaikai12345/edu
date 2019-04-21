<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;

/**
 * @SWG\Definition(
 *      definition="Reply",
 *      type="object",
 *      required={},
 *      description="回复模型",
 *      @SWG\Property(property="id",type="string",readOnly=true),
 *      @SWG\Property(property="content",type="string",description="回复内容"),
        @SWG\Property(property="status",type="string",enum={"qualified","violation"},description="状态"),
 *      @SWG\Property(property="user_id",type="integer",description="回复人",readOnly=true),
 *      @SWG\Property(property="course_id",type="integer",description="课程",readOnly=true),
 *      @SWG\Property(property="plan_id",type="integer",description="版本",readOnly=true),
 *      @SWG\Property(property="task_id",type="integer",description="任务",readOnly=true),
 *      @SWG\Property(property="topic_id",type="integer",description="话题",readOnly=true),
 *      @SWG\Property(property="is_elite",type="bool",default=false,description="是否精华",readOnly=true),
 *      @SWG\Property(property="created_at",type="string",format="date-time",description="创建时间",readOnly=true),
 *      @SWG\Property(property="updated_at",type="string",format="date-time",description="更新时间",readOnly=true)
 * )
 * @SWG\Response(
 *      response="ReplyPagination",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/Reply")),
 *          @SWG\Property(property="meta",ref="$/definitions/MetaProperty")
 *      )
 * )
 * @SWG\Response(
 *      response="ReplyResponse",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/Reply"))
 *      )
 * )
 *
 * // Fillable parameters 表单参数
 * @SWG\Parameter(parameter="ReplyForm-content",name="content",required=true,in="formData",type="string",description="内容")
 * @SWG\Parameter(parameter="ReplyForm-status",name="status",required=true,in="formData",type="string",enum={"qualified","violation"},description="状态：合格、违规")
 * @SWG\Parameter(parameter="ReplyForm-is_elite",name="is_elite",required=true,in="formData",type="boolean",description="是否加精")
 *
 * // Searchable parameters 搜索参数
 * @SWG\Parameter(parameter="ReplyQuery-status",name="status",in="query",type="string",enum={"qualified","violation"},description="状态：合格、违规")
 * @SWG\Parameter(parameter="ReplyQuery-is_elite",name="is_elite",in="query",type="boolean",description="是否加精")
 * @SWG\Parameter(parameter="ReplyQuery-user_id",name="user_id",in="query",type="integer",description="用户ID")
 * @SWG\Parameter(parameter="ReplyQuery-user:username",name="user:username",in="query",type="string",description="用户名")
 * @SWG\Parameter(parameter="ReplyQuery-course_id",name="course_id",in="query",type="integer",description="课程ID")
 * @SWG\Parameter(parameter="ReplyQuery-course:title",name="course:title",in="query",type="string",description="课程标题")
 * @SWG\Parameter(parameter="ReplyQuery-plan_id",name="plan_id",in="query",type="integer",description="版本ID")
 * @SWG\Parameter(parameter="ReplyQuery-plan:title",name="plan:title",in="query",type="string",description="版本标题")
 * @SWG\Parameter(parameter="ReplyQuery-task_id",name="task_id",in="query",type="integer",description="任务ID")
 * @SWG\Parameter(parameter="ReplyQuery-task:title",name="task:title",in="query",type="string",description="任务标题")
 * @SWG\Parameter(parameter="ReplyQuery-topic_id",name="topic_id",in="query",type="integer",description="话题ID")
 * @SWG\Parameter(parameter="ReplyQuery-topic:title",name="topic:title",in="query",type="string",description="话题")
 * @SWG\Parameter(parameter="ReplyQuery-created_at",name="created_at",in="query",type="string",description="发布时间")
 *
 * // sortable parameters 排序参数
 * @SWG\Parameter(
 *     parameter="Reply-sort",
 *     name="sort",
 *     in="query",
 *     type="string",
 *     description="使用方法见 sortable；[created_at]",
 * )
 *
 * // Include parameters 关联参数
 * @SWG\Parameter(
 *     parameter="Reply-include",
 *     name="include",
 *     in="query",
 *     type="string",
 *     description="使用方法见 Include；{user:作者,course:附属课程,plan:附属版本,task:附属任务,topic:附属话题}",
 * )
 */
class Reply extends Model
{
    use SortableTrait, SearchableTrait;

    /**
     * @var string 话题回复
     */
    protected $table = 'replies';

    /**
     * @var array
     */
    public $sortable = ['created_at'];

    /**
     * @var array
     */
    public $searchable = [
        'status',
        'is_elite',
        'user_id',
        'user:username',
        'course_id',
        'course:title',
        'plan_id',
        'plan:title',
        'topic_id',
        'topic:title',
        'created_at',
    ];

    /**
     * @var array
     */
    protected $fillable = ['content'];

    /**
     * 用户
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
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
     * 教学版本
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
     * 话题
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }
}
