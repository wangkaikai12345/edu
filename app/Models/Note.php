<?php

namespace App\Models;

use Carbon\Carbon;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;

/**
 * @SWG\Definition(
 *      definition="Note",
 *      type="object",
 *      required={},
 *      description="笔记模型",
 *      @SWG\Property(property="id",type="string",readOnly=true),
 *      @SWG\Property(property="course_id",type="integer",description="课程",readOnly=true),
 *      @SWG\Property(property="plan_id",type="integer",description="版本",readOnly=true),
 *      @SWG\Property(property="task_id",type="integer",description="任务",readOnly=true),
 *      @SWG\Property(property="user_id",type="integer",description="用户",readOnly=true),
 *      @SWG\Property(property="content",type="string",description="内容"),
 *      @SWG\Property(property="is_public",type="bool",default=true,description="是否公开"),
 *      @SWG\Property(property="created_at",type="string",format="date-time",description="创建时间",readOnly=true),
 *      @SWG\Property(property="updated_at",type="string",format="date-time",description="更新时间",readOnly=true)
 * )
 * @SWG\Response(
 *      response="NotePagination",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/Note")),
 *          @SWG\Property(property="meta",ref="$/definitions/MetaProperty")
 *      )
 * )
 * @SWG\Response(
 *      response="NoteResponse",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/Note"))
 *      )
 * )
 *
 * // Fillable parameters 表单参数
 * @SWG\Parameter(parameter="NoteForm-content",name="content",required=true,in="formData",type="string",description="笔记内容")
 * @SWG\Parameter(parameter="NoteForm-is_public",name="content",required=true,in="formData",type="boolean",default=true,description="是否公开")
 *
 * // Searchable parameters 搜索参数
 * @SWG\Parameter(parameter="NoteQuery-course_id",name="course_id",in="query",type="integer",description="课程ID")
 * @SWG\Parameter(parameter="NoteQuery-course:title",name="course:title",in="query",type="string",description="课程标题")
 * @SWG\Parameter(parameter="NoteQuery-plan_id",name="plan_id",in="query",type="integer",description="版本ID")
 * @SWG\Parameter(parameter="NoteQuery-plan:title",name="plan:title",in="query",type="integer",description="版本标题")
 * @SWG\Parameter(parameter="NoteQuery-task_id",name="task_id",in="query",type="integer",description="任务ID")
 * @SWG\Parameter(parameter="NoteQuery-task:title",name="task:title",in="query",type="integer",description="任务标题")
 * @SWG\Parameter(parameter="NoteQuery-user_id",name="user_id",in="query",type="integer",description="用户ID")
 * @SWG\Parameter(parameter="NoteQuery-user:username",name="user:username",in="query",type="string",description="用户账户")
 * @SWG\Parameter(parameter="NoteQuery-is_public",name="is_public",in="query",type="boolean",description="是否公开")
 *
 * // sortable parameters 排序参数
 * @SWG\Parameter(
 *     parameter="Note-sort",
 *     name="sort",
 *     in="query",
 *     type="string",
 *     description="使用方法见 sortable；[created_at,updated_at]",
 * )
 *
 * // Include parameters 关联参数
 * @SWG\Parameter(
 *     parameter="Note-include",
 *     name="include",
 *     in="query",
 *     type="string",
 *     description="使用方法见 Include；{course:课程,plan:版本,task:任务,user:用户}",
 * )
 */
class Note extends Model
{
    use SearchableTrait, SortableTrait, SoftDeletes;

//    use Cachable;
    /**
     * @var string 笔记
     */
    protected $table = 'notes';

    /**
     * @var array
     */
    public $sortable = ['created_at', 'updated_at'];

    /**
     * @var array
     */
    public $searchable = [
        'course_id',
        'course:title',
        'plan_id',
        'plan:title',
        'task_id',
        'task:title',
        'user_id',
        'user:username',
        'is_public',
    ];

    /**
     * @var array 批量赋值字段
     */
    protected $fillable = ['content', 'is_public', 'collection'];

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @var string sortable default sort
     */
    public $defaultSortCriteria = 'created_at,desc';

    /**
     * @var array
     */
    public static $baseFields = ['id', 'content', 'updated_at'];

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
     * 版本笔记的版本信息
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
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
     * 任务
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * 收藏
     *
     * @return \Illuminate\Database\Eloquent\Relations\morphMany
     */
    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'model');
    }

    /**
     * 通过日期范围查询新增笔记
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
