<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;

/**
 * @SWG\Definition(
 *      definition="PlanTeacher",
 *      type="object",
 *      required={},
 *      description="版本教师模型",
 *      @SWG\Property(property="id",type="integer",readOnly=true),
 *      @SWG\Property(property="course_id",type="integer",description="课程",readOnly=true),
 *      @SWG\Property(property="plan_id",type="integer",description="版本",readOnly=true),
 *      @SWG\Property(property="user_id",type="integer",description="用户"),
 *      @SWG\Property(property="seq",type="integer",default=0,description="推荐序号"),
 *      @SWG\Property(property="created_at",type="string",format="date-time",description="创建时间",readOnly=true),
 *      @SWG\Property(property="updated_at",type="string",format="date-time",description="更新时间",readOnly=true)
 * )
 * @SWG\Response(
 *      response="PlanTeacherPagination",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/PlanTeacher")),
 *          @SWG\Property(property="meta",ref="$/definitions/MetaProperty")
 *      )
 * )
 * @SWG\Response(
 *      response="PlanTeacherResponse",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/PlanTeacher"))
 *      )
 * )
 * @SWG\Definition(
 *      definition="PlanTeacherSearchable",
 *      type="object",
 *      description="[可搜索参数使用文档](https://github.com/jedrzej/searchable/blob/master/README.md)",
 *      @SWG\Property(property="user:username",type="string",description="假话教师用户名"),
 *      @SWG\Property(property="course:title",type="string",description="课程名称"),
 *      @SWG\Property(property="plan:title",type="string",description="版本名称"),
 * )
 *
 *
 * // Fillable parameters 表单参数
 * @SWG\Parameter(parameter="PlanTeacherForm-user_id",name="user_id",required=true,in="formData",type="integer",description="用户ID")
 * @SWG\Parameter(parameter="PlanTeacherForm-seq",name="seq",required=true,in="formData",type="integer",default=0,description="排序系数（降序）")
 *
 * // Searchable parameters 搜索参数
 * @SWG\Parameter(parameter="PlanTeacherQuery-course_id",name="course_id",in="query",type="integer",description="课程ID")
 * @SWG\Parameter(parameter="PlanTeacherQuery-course:title",name="course:title",in="query",type="string",description="课程标题")
 * @SWG\Parameter(parameter="PlanTeacherQuery-plan_id",name="plan_id",in="query",type="integer",description="版本ID")
 * @SWG\Parameter(parameter="PlanTeacherQuery-plan:title",name="plan:title",in="query",type="integer",description="版本标题")
 * @SWG\Parameter(parameter="PlanTeacherQuery-user_id",name="user_id",in="query",type="integer",description="用户ID")
 * @SWG\Parameter(parameter="PlanTeacherQuery-user:username",name="user:username",in="query",type="string",description="用户账户")
 *
 * // sortable parameters 排序参数
 * @SWG\Parameter(
 *     parameter="PlanTeacher-sort",
 *     name="sort",
 *     in="query",
 *     type="string",
 *     description="使用方法见 sortable；[seq,created_at]",
 * )
 *
 * // Include parameters 关联参数
 * @SWG\Parameter(
 *     parameter="PlanTeacher-include",
 *     name="include",
 *     in="query",
 *     type="string",
 *     description="使用方法见 Include；{course:课程,plan:版本,user:用户}",
 * )
 */
class PlanTeacher extends Model
{
    use SortableTrait, SearchableTrait, SortableTrait;

//    use Cachable;
    /**
     * @var string 版本教师
     */
    protected $table = 'plan_teachers';

    /**
     * @var array
     */
    public $fillable = ['user_id', 'seq'];

    /**
     * @var array
     */
    public $sortable = ['*'];

    /**
     * @var array
     */
    public $searchable = [
        'user_id',
        'user:username',
        'course_id',
        'course:title',
        'plan_id',
        'plan:title',
    ];

    /**
     * @var string 降序
     */
    public $defaultSortCriteria = 'created_at,desc';

    /**
     * 用户
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * 课程
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    /**
     * 版本
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id', 'id');
    }
}
