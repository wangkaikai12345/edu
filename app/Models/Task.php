<?php

namespace App\Models;

use App\Enums\Status;
use App\Traits\HashIdTrait;
use App\Traits\ModelHasStatusTrait;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;

/**
 * @SWG\Definition(
 *      definition="Task",
 *      type="object",
 *      required={},
 *      description="任务模型",
 *      @SWG\Property(property="id",type="string",readOnly=true),
 *      @SWG\Property(property="course_id",type="integer",description="课程",readOnly=true),
 *      @SWG\Property(property="plan_id",type="integer",description="版本",readOnly=true),
 *      @SWG\Property(property="chapter_id",type="integer",description="章节",readOnly=true),
 *      @SWG\Property(property="title",type="string",description="标题",readOnly=true),
 *      @SWG\Property(property="type",type="string",enum={"task","preparation","exercise","homework","extra"},default="task",description="任务类型：预习、任务、练习、作业、课外"),
 *      @SWG\Property(property="is_free",type="bool",default=false,description="是否免费"),
 *      @SWG\Property(property="is_optional",type="bool",default=false,description="是否可选"),
 *      @SWG\Property(property="status",type="string",description="状态"),
 *      @SWG\Property(property="user_id",type="integer",description="用户"),
 *      @SWG\Property(property="seq",type="integer",description="任务排序"),
 *      @SWG\Property(property="started_at",type="string",format="date-time",description="开始时间"),
 *      @SWG\Property(property="ended_at",type="string",format="date-time",description="结束时间"),
 *      @SWG\Property(property="target_id",type="integer",description="模型ID"),
 *      @SWG\Property(property="target_type",type="string",enum={"video","audio","ppt","doc","text","test"},description="任务模式：视频、音频、ppt、doc、图文、考试",readOnly=true),
 *      @SWG\Property(property="length",type="integer",description="任务时长 单位：秒"),
 *      @SWG\Property(property="finish_type",type="string",enum={"time","end"},description="任务完成类型"),
 *      @SWG\Property(property="finish_detail",type="integer",description="指定任务完成时间"),
 *      @SWG\Property(property="created_at",type="string",format="date-time",description="创建时间",readOnly=true),
 *      @SWG\Property(property="updated_at",type="string",format="date-time",description="更新时间",readOnly=true)
 * )
 * @SWG\Response(
 *      response="TaskPagination",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/Task")),
 *          @SWG\Property(property="meta",ref="$/definitions/MetaProperty")
 *      )
 * )
 * @SWG\Response(
 *      response="TaskResponse",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/Task"))
 *      )
 * )
 * // Form 表单数据
 * @SWG\Parameter(parameter="TaskForm-title",name="title",in="formData",type="string",description="名称")
 * @SWG\Parameter(parameter="TaskForm-is_free",name="is_free",in="formData",type="boolean",description="是否免费")
 * @SWG\Parameter(parameter="TaskForm-is_optional",name="is_optional",in="formData",type="boolean",description="是否选修")
 * @SWG\Parameter(parameter="TaskForm-type",name="type",in="formData",type="string",enum={"task","preparation","exercise","homework","extra"},default="task",description="任务类型：预习、任务、练习、作业、课外")
 * @SWG\Parameter(parameter="TaskForm-length",name="length",in="formData",type="string",description="任务时长")
 * @SWG\Parameter(parameter="TaskForm-started_at",name="started_at",in="formData",type="string",description="任务开始时间")
 * @SWG\Parameter(parameter="TaskForm-ended_at",name="ended_at",in="formData",type="string",description="任务结束时间")
 * @SWG\Parameter(parameter="TaskForm-finish_type",name="finish_type",in="formData",type="string",enum={"time","end"},description="任务完成类型：指定时长/直到结束")
 * @SWG\Parameter(parameter="TaskForm-finish_detail",name="finish_detail",in="formData",type="string",description="指定时长")
 * @SWG\Parameter(parameter="TaskForm-target_type",name="target_type",in="formData",type="string",enum={"video","audio","ppt","doc","text","test"},description="任务模式：视频、音频、PPT、Doc、图文、考试")
 * @SWG\Parameter(parameter="TaskForm-target_id",name="target_id",in="formData",type="integer",description="任务ID")
 *
 * // sortable parameters 排序参数
 * @SWG\Parameter(
 *     parameter="Task-sort",
 *     name="sort",
 *     in="query",
 *     type="string",
 *     description="使用方法见 sortable；[created_at,seq]",
 * )
 * // Include parameters 关联参数
 * @SWG\Parameter(
 *     parameter="Task-include",
 *     name="include",
 *     in="query",
 *     type="string",
 *     description="使用方法见 Include；{chapter:附属章节,results:任务完成情况,user:用户,target:任务详情}",
 * )
 */
class Task extends Model
{
    use SoftDeletes, SortableTrait, SearchableTrait, ModelHasStatusTrait, HashIdTrait;
//    use Cachable;
    /**
     * @var string 任务
     */
    protected $table = 'tasks';

    /**
     * @var array
     */
    protected $sortable = ['*'];

    /**
     * @var array
     */
    protected $searchable = [
        'user_id',
        'course_id',
        'plan_id',
        'chapter_id',
        'title',
        'is_free',
        'is_optional',
        'status',
        'started_at',
        'ended_at',
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'is_free',
        'is_optional',
        'type',
        'length',
        'started_at',
        'ended_at',
        'finish_type',
        'finish_detail',
        'target_type',
        'target_id',
        'keyword',
        'describe',
    ];

    /**
     * @var array
     */
    protected $dates = ['started_at', 'ended_at'];

    /**
     * @var array
     */
    protected $casts = [
        'is_free' => 'boolean',
        'is_optional' => 'boolean',
    ];

    /**
     * @var array
     */
    public static $baseFields = ['id', 'title'];

    /**
     * 创建人
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
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
     * 章节
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    /**
     * 结果
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function results()
    {
        return $this->hasMany(TaskResult::class, 'task_id', 'id');
    }

    public function scopeCurrentResult($query, $userId)
    {
        $res = $this->results()->where('user_id', $userId)->first();

        return $res ? $res->status : '';
    }

    /**
     * 任务下的笔记
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * @author 王凯
     */
    public function notes()
    {
        return $this->hasMany(Note::class, 'task_id', 'id');
    }

    /**
     * 任务下的话题
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * @author 王凯
     */
    public function topics()
    {
        return $this->hasMany(Topic::class, 'task_id', 'id');
    }

    public function replies()
    {
        return $this->hasMany(Reply::class, 'task_id', 'id');
    }

    /**
     * 任务类型
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function target()
    {
        return $this->morphTo('target', 'target_type', 'target_id');
    }

}
