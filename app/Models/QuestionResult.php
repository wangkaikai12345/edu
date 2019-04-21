<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;

/**
 * @SWG\Definition(
 *      definition="QuestionResult",
 *      type="object",
 *      required={},
 *      description="答题记录模型",
 *      @SWG\Property(property="id",type="string",readOnly=true),
 *      @SWG\Property(property="task_id",type="integer",description="任务"),
 *      @SWG\Property(property="test_id",type="integer",description="考试",readOnly=true),
 *      @SWG\Property(property="question_id",type="integer",description="题目"),
 *      @SWG\Property(property="user_id",type="string",description="用户"),
 *      @SWG\Property(property="answers",type="string",description="答案"),
 *      @SWG\Property(property="is_right",type="boolean",description="是否正确"),
 *      @SWG\Property(property="score",type="integer",description="得分"),
 *      @SWG\Property(property="created_at",type="string",format="date-time",description="创建时间",readOnly=true),
 *      @SWG\Property(property="updated_at",type="string",format="date-time",description="更新时间",readOnly=true)
 * )
 * @SWG\Response(
 *      response="QuestionResultPagination",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/QuestionResult")),
 *          @SWG\Property(property="meta",ref="$/definitions/MetaProperty")
 *      )
 * )
 * @SWG\Response(
 *      response="QuestionResultResponse",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/QuestionResult"))
 *      )
 * )
 *
 * // Fillable parameters 表单参数
 * @SWG\Parameter(parameter="QuestionResultForm-task_id",name="task_id",required=true,in="formData",type="integer",description="任务ID")
 * @SWG\Parameter(parameter="QuestionResultForm-question_id",name="question_id",required=true,in="formData",type="integer",description="题目ID")
 * @SWG\Parameter(parameter="QuestionResultForm-answers",name="answers",required=true,in="formData",type="array",@SWG\Items(type="integer"),description="答案数组")
 *
 * // Searchable parameters 搜索参数
 * @SWG\Parameter(parameter="QuestionResultQuery-task_id",name="task_id",in="query",type="integer",description="任务ID")
 * @SWG\Parameter(parameter="QuestionResultQuery-task:title",name="task:title",in="query",type="string",description="任务标题")
 * @SWG\Parameter(parameter="QuestionResultQuery-test_id",name="test_id",in="query",type="integer",description="考试ID")
 * @SWG\Parameter(parameter="QuestionResultQuery-test:title",name="test:title",in="query",type="string",description="考试标题")
 * @SWG\Parameter(parameter="QuestionResultQuery-question_id",name="question_id",in="query",type="integer",description="题目ID")
 * @SWG\Parameter(parameter="QuestionResultQuery-question:title",name="question:title",in="query",type="string",description="题目标题")
 * @SWG\Parameter(parameter="QuestionResultQuery-user_id",name="user_id",in="query",type="integer",description="答题人ID")
 * @SWG\Parameter(parameter="QuestionResultQuery-user:username",name="user:username",in="query",type="string",description="答题人账户")
 * @SWG\Parameter(parameter="QuestionResultQuery-is_right",name="is_right",in="query",type="boolean",description="是否正确")
 *
 * // sortable parameters 排序参数
 * @SWG\Parameter(
 *     parameter="QuestionResult-sort",
 *     name="sort",
 *     in="query",
 *     type="string",
 *     description="使用方法见 sortable；[created_at]",
 * )
 *
 * // Include parameters 关联参数
 * @SWG\Parameter(
 *     parameter="QuestionResult-include",
 *     name="include",
 *     in="query",
 *     type="string",
 *     description="使用方法见 Include；{task:附属任务,test:附属考试,question:附属题目,user:答题人}",
 * )
 */
class QuestionResult extends Model
{
    use SearchableTrait, SortableTrait;

    /**
     * @var string 题目答案
     */
    protected $table = 'question_results';

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    public $fillable = [
        'task_id',
        'paper_result_id',
        'paper_id',
        'question_id',
        'user_id',
        'objective_answer',
        'subjective_answer',
        'explain',
        'status',
        'type',
        'rate',
        'score',
    ];

    /**
     * @var array
     */
    protected $casts = ['answers' => 'array'];

    /**
     * @var array
     */
    public $searchable = [
        'task_id',
        'task:title',
        'test_id',
        'test:title',
        'user_id',
        'user:username',
        'question_id',
        'question:title',
        'is_right',
    ];

    /**
     * @var array
     */
    public $sortable = [
        'created_at',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function paper()
    {
        return $this->belongsTo(Paper::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
