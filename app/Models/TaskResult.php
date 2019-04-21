<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jedrzej\Sortable\SortableTrait;

/**
 * @SWG\Definition(
 *      definition="TaskResult",
 *      type="object",
 *      required={},
 *      description="任务结果模型",
 *      @SWG\Property(property="id",type="string",readOnly=true),
 *      @SWG\Property(property="course_id",type="integer",description="课程",readOnly=true),
 *      @SWG\Property(property="plan_id",type="integer",description="版本",readOnly=true),
 *      @SWG\Property(property="task_id",type="integer",description="任务",readOnly=true),
 *      @SWG\Property(property="user_id",type="integer",description="用户",readOnly=true),
 *      @SWG\Property(property="status",type="string",enum={"start","finish"},default="start",description="状态",readOnly=true),
 *      @SWG\Property(property="finished_at",type="string",format="date-time",description="完成时间",readOnly=true),
 *      @SWG\Property(property="time",type="integer",description="学习时间"),
 *      @SWG\Property(property="created_at",type="string",format="date-time",description="创建时间",readOnly=true),
 *      @SWG\Property(property="updated_at",type="string",format="date-time",description="更新时间",readOnly=true)
 * )
 * @SWG\Response(
 *      response="TaskResultPagination",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/TaskResult")),
 *          @SWG\Property(property="meta",ref="$/definitions/MetaProperty")
 *      )
 * )
 * @SWG\Response(
 *      response="TaskResultResponse",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/TaskResult"))
 *      )
 * )
 */
class TaskResult extends Model
{
    use SoftDeletes, SortableTrait;

//    use Cachable;
    /**
     * @var string 任务结果表
     */
    protected $table = 'task_results';

    /**
     * @var array
     */
    protected $sortable = ['*'];

    /**
     * @var array
     */
    protected $fillable = ['status', 'time'];

    /**
     * @var array
     */
    protected $dates = ['finished_at'];

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
     * 版本
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
}
