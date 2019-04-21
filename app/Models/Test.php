<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;

/**
 * @SWG\Definition(
 *      definition="Test",
 *      type="object",
 *      required={},
 *      description="考试模型",
 *      @SWG\Property(property="id",type="integer",readOnly=true),
 *      @SWG\Property(property="title",type="string",description="考试名称"),
 *      @SWG\Property(property="course_id",type="integer",description="课程",readOnly=true),
 *      @SWG\Property(property="user_id",type="integer",description="创建人",readOnly=true),
 *      @SWG\Property(property="total_score",type="integer",description="总分",readOnly=true),
 *      @SWG\Property(property="questions_count",type="integer",default=0,description="题目个数",readOnly=true),
 *      @SWG\Property(property="single_count",type="integer",default=0,default=false,description="单选个数",readOnly=true),
 *      @SWG\Property(property="multiple_count",type="integer",default=0,description="多选个数",readOnly=true),
 *      @SWG\Property(property="judge_count",type="integer",default=0,description="判断个数",readOnly=true),
 *      @SWG\Property(property="created_at",type="string",format="date-time",description="创建时间",readOnly=true),
 *      @SWG\Property(property="updated_at",type="string",format="date-time",description="更新时间",readOnly=true)
 * )
 * @SWG\Response(
 *      response="TestPagination",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/Test")),
 *          @SWG\Property(property="meta",ref="$/definitions/MetaProperty")
 *      )
 * )
 * @SWG\Response(
 *      response="TestResponse",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/Test"))
 *      )
 * )
 *
 * // Fillable parameters 表单参数
 * @SWG\Parameter(parameter="TestForm-title",name="title",required=true,in="formData",type="string",maxLength=191,description="考试名称")
 * @SWG\Parameter(parameter="TestForm-course_id",name="course_id",in="formData",type="integer",description="课程ID")
 *
 * // Searchable parameters 搜索参数
 * @SWG\Parameter(parameter="TestQuery-title",name="user_id",in="query",type="integer",description="作者ID")
 * @SWG\Parameter(parameter="TestQuery-user_id",name="user_id",in="query",type="integer",description="作者ID")
 * @SWG\Parameter(parameter="TestQuery-user:username",name="user:username",in="query",type="string",description="作者用户名")
 * @SWG\Parameter(parameter="TestQuery-course_id",name="course_id",in="query",type="integer",description="课程ID")
 * @SWG\Parameter(parameter="TestQuery-course:title",name="course:title",in="query",type="string",description="课程标题")
 * @SWG\Parameter(parameter="TestQuery-total_score",name="total_score",in="query",type="integer",description="总分")
 * @SWG\Parameter(parameter="TestQuery-questions_count",name="questions_count",in="query",type="integer",description="题目个数")
 * @SWG\Parameter(parameter="TestQuery-single_count",name="single_count",in="query",type="integer",description="单选个数")
 * @SWG\Parameter(parameter="TestQuery-multiple_count",name="multiple_count",in="query",type="integer",description="多选个数")
 * @SWG\Parameter(parameter="TestQuery-judge_count",name="judge_count",in="query",type="integer",description="判断个数")
 * @SWG\Parameter(parameter="TestQuery-created_at",name="created_at",in="query",type="string",description="创建时间")
 *
 * // sortable parameters 排序参数
 * @SWG\Parameter(
 *     parameter="Test-sort",
 *     name="sort",
 *     in="query",
 *     type="string",
 *     description="使用方法见 sortable；[created_at]",
 * )
 *
 * // Include parameters 关联参数
 * @SWG\Parameter(
 *     parameter="Test-include",
 *     name="include",
 *     in="query",
 *     type="string",
 *     description="使用方法见 Include；{user:作者,plan:版本,course:课程}",
 * )
 */
class Test extends Model
{
    use SortableTrait, SearchableTrait;

    /**
     * @var string 考试
     */
    protected $table = 'papers';

    /**
     * @var array
     */
    public $fillable = ['title'];

    /**
     * @var array
     */
    public $searchable = [
        'title',
        'course:title',
        'course_id',
        'user_id',
        'user:username',
        'total_score',
        'questions_count',
        'single_count',
        'multiple_count',
        'judge_count',
        'created_at',
    ];

    /**
     * 排序
     *
     * @var array
     */
    public $sortable = ['created_at'];

    public $defaultSortCriteria = 'created_at,desc';

    /**
     * 考题
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function questions()
    {
        return $this->belongsToMany(Question::class, 'paper_questions')->withPivot('score');
    }

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
     * 所属课程
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * 考试记录
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function results()
    {
        return $this->hasMany(TestResult::class);
    }

    /**
     * 考试所属任务（一个考试允许从属于多个任务）
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function tasks()
    {
        return $this->morphMany(Task::class, 'target');
    }
}
