<?php

namespace App\Models;

use App\Traits\HashIdTrait;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;

/**
 * @SWG\Definition(
 *     definition="Chapter",
 *     type="object",
 *     required={"title","type"},
 *     description="章节模型",
 *     @SWG\Property(property="id",type="string",readOnly=true),
 *     @SWG\Property(property="course_id",type="integer",description="课程",readOnly=true),
 *     @SWG\Property(property="plan_id",type="integer",description="版本",readOnly=true),
 *     @SWG\Property(property="user_id",type="integer",description="用户",readOnly=true),
 *     @SWG\Property(property="title",type="string",description="章节标题",maxLength=36),
 *     @SWG\Property(property="type",type="string",enum={"chapter","section"},description="章/节",default="chapter"),
 *     @SWG\Property(property="seq",type="integer",description="排序序号（在添加时自动生成，允许修改）",readOnly=true),
 *     @SWG\Property(property="created_at",type="string",format="date-time",description="创建时间",readOnly=true),
 *     @SWG\Property(property="updated_at",type="string",format="date-time",description="更新时间",readOnly=true)
 * )
 * @SWG\Response(
 *   response="ChapterResponse",
 *   description="数据响应",
 *   @SWG\Schema(
 *     @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/Chapter"))
 *   )
 * )
 *
 * // Fillable parameters 表单参数
 * @SWG\Parameter(parameter="ChapterForm-title",name="title",required=true,in="formData",type="string",minLength=1,maxLength=191,description="章节标题")
 * @SWG\Parameter(parameter="ChapterForm-parent_id",name="parent_id",required=true,in="formData",type="integer",description="章节ID",default=0)
 *
 * // Searchable parameters 搜索参数
 * @SWG\Parameter(parameter="ChapterQuery-title",name="title",in="query",type="string",description="标题")
 * @SWG\Parameter(parameter="ChapterQuery-course:title",name="course:title",in="query",type="string",description="课程标题")
 * @SWG\Parameter(parameter="ChapterQuery-course_id",name="course_id",in="query",type="integer",description="课程ID")
 * @SWG\Parameter(parameter="ChapterQuery-plan_id",name="plan_id",in="query",type="string",description="版本ID")
 * @SWG\Parameter(parameter="ChapterQuery-user:username",name="user:username",in="query",type="string",description="用户名")
 * @SWG\Parameter(parameter="ChapterQuery-user_id",name="user_id",in="query",type="string",description="用户ID")
 * @SWG\Parameter(parameter="ChapterQuery-type",name="type",in="query",type="string",description="类型")
 * @SWG\Parameter(parameter="ChapterQuery-created_at",name="created_at",in="query",type="string",description="创建时间")
 *
 * // sortable parameters 排序参数
 * @SWG\Parameter(
 *     parameter="Chapter-sort",
 *     name="sort",
 *     in="query",
 *     type="string",
 *     description="使用方法见 sortable；[created_at]",
 * )
 *
 * // Include parameters 关联参数
 * @SWG\Parameter(
 *     parameter="Chapter-include",
 *     name="include",
 *     in="query",
 *     type="string",
 *     description="使用方法见 Include；{course:课程,plan:版本,user:创建人,children:子集,tasks:任务}",
 * )
 */
class Chapter extends Model
{
    use SearchableTrait, SortableTrait, SoftDeletes, HashIdTrait;
//    use Cachable;
    /**
     * @var string 章节表
     */
    protected $table = 'chapters';

    /**
     * @var array 批量赋值字段
     */
    protected $fillable = ['title', 'goals', 'parent_id'];

    /**
     * @var array
     */
    public $sortable = ['created_at'];

    /**
     * @var array
     */
    public $searchable = [
        'title',
        'course:title',
        'course_id',
        'plan_id',
        'user:username',
        'user_id',
        'type',
        'created_at',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'parent_id' => 'integer',
    ];

    /**
     * @var array
     */
    public static $baseFields = ['id', 'title', 'parent_id'];

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
     * 创建人
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 任务
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * 子集
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany;
     */
    public function children()
    {
        return $this->hasMany(Chapter::class, 'parent_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(Chapter::class, 'parent_id', 'id');
    }

    public function classroomResult($classroom)
    {
        return ClassroomResult::where('user_id', auth('web')->id())
            ->where('classroom_id', $classroom)
            ->where('chapter_id', $this->id)
            ->first();
    }
}
