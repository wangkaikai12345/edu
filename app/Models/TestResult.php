<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;

/**
 * @SWG\Definition(
 *      definition="TestResult",
 *      type="object",
 *      required={},
 *      description="考试结果模型",
 *      @SWG\Property(property="id",type="string",readOnly=true),
 *      @SWG\Property(property="task_id",type="integer",description="任务",readOnly=true),
 *      @SWG\Property(property="test_id",type="integer",description="考试",readOnly=true),
 *      @SWG\Property(property="user_id",type="integer",description="用户",readOnly=true),
 *      @SWG\Property(property="right_count",type="integer",description="正确个数",readOnly=true),
 *      @SWG\Property(property="question_count",type="integer",description="题目个数",readOnly=true),
 *      @SWG\Property(property="score",type="integer",description="成绩",readOnly=true),
 *      @SWG\Property(property="total_score",type="integer",description="卷面分",readOnly=true),
 *      @SWG\Property(property="finished_count",type="integer",description="答题个数",readOnly=true),
 *      @SWG\Property(property="is_finished",type="boolean",description="是否已完成",readOnly=true),
 *      @SWG\Property(property="created_at",type="string",format="date-time",description="创建时间",readOnly=true),
 *      @SWG\Property(property="updated_at",type="string",format="date-time",description="更新时间",readOnly=true)
 * )
 * @SWG\Response(
 *      response="TestResultPagination",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/TestResult")),
 *          @SWG\Property(property="meta",ref="$/definitions/MetaProperty")
 *      )
 * )
 * @SWG\Response(
 *      response="TestResultResponse",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/TestResult"))
 *      )
 * )
 *
 * // Searchable parameters 搜索参数
 * @SWG\Parameter(parameter="TestResultQuery-task_id",name="task_id",in="query",type="integer",description="任务ID")
 * @SWG\Parameter(parameter="TestResultQuery-task:title",name="task:title",in="query",type="string",description="任务标题")
 * @SWG\Parameter(parameter="TestResultQuery-test_id",name="test_id",in="query",type="integer",description="考试ID")
 * @SWG\Parameter(parameter="TestResultQuery-test:title",name="test:title",in="query",type="string",description="考试标题")
 * @SWG\Parameter(parameter="TestResultQuery-user_id",name="user_id",in="query",type="integer",description="作者ID")
 * @SWG\Parameter(parameter="TestResultQuery-user:username",name="user:username",in="query",type="string",description="作者用户名")
 * @SWG\Parameter(parameter="TestResultQuery-right_count",name="right_count",in="query",type="integer",description="正确数")
 * @SWG\Parameter(parameter="TestResultQuery-questions_count",name="questions_count",in="query",type="integer",description="题目个数")
 * @SWG\Parameter(parameter="TestResultQuery-score",name="score",in="query",type="integer",description="得分")
 * @SWG\Parameter(parameter="TestResultQuery-total_score",name="total_score",in="query",type="integer",description="卷面分")
 * @SWG\Parameter(parameter="TestResultQuery-is_finished",name="is_finished",in="query",type="boolean",description="是否已完成")
 * @SWG\Parameter(parameter="TestResultQuery-created_at",name="created_at",in="query",type="string",description="创建时间")
 *
 * // sortable parameters 排序参数
 * @SWG\Parameter(
 *     parameter="TestResult-sort",
 *     name="sort",
 *     in="query",
 *     type="string",
 *     description="使用方法见 sortable；[right_count,score,created_at]",
 * )
 *
 * // Include parameters 关联参数
 * @SWG\Parameter(
 *     parameter="TestResult-include",
 *     name="include",
 *     in="query",
 *     type="string",
 *     description="使用方法见 Include；{user:作者,task:任务,test:考试}",
 * )
 */
class TestResult extends Model
{
    use SearchableTrait, SortableTrait;

    /**
     * @var string 考试结果
     */
    protected $table = 'paper_results';

    /**
     * @var array 可搜索字段
     */
    public $searchable = [
        'user:username',
        'test:title',
        'test_id',
        'right_count',
        'score',
        'is_finished',
        'total_score',
        'is_finished',
    ];

    /**
     * @var array 可排序字段
     */
    public $sortable = ['right_count', 'score', 'created_at'];

    /**
     * @var array 批量赋值字段
     */
    protected $fillable = [
        'task_id',
        'test_id',
        'user_id',
        'right_count',
        'questions_count',
        'is_finished',
        'finished_count',
        'score',
        'total_score',
    ];

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
     * 所属任务
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * 所属考试
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function test()
    {
        return $this->belongsTo(Test::class);
    }
}
