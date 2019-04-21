<?php

namespace App\Models;

use App\Enums\FavoriteType;
use App\Traits\HashIdTrait;
use App\Traits\ModelHasStatusTrait;
use App\Traits\NotCopyTrait;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;

/**
 * @SWG\Definition(
 *      definition="Course",
 *      type="object",
 *      required={"coin"},
 *      description="课程模型",
 *      @SWG\Property(property="id",type="integer",readOnly=true),
 *      @SWG\Property(property="title",type="string",description="课程名称",maxLength=32),
 *      @SWG\Property(property="subtitle",type="string",description="课程副题",maxLength=32,default=null),
 *      @SWG\Property(property="summary",type="string",description="课程简介",default=null),
 *      @SWG\Property(property="category_id",type="integer",description="课程分类",default=null),
 *      @SWG\Property(property="goals",type="array",@SWG\Items(type="string"),description="课程目标",default=null),
 *      @SWG\Property(property="audiences",type="array",@SWG\Items(type="string"),description="目标群体",default=null),
 *      @SWG\Property(property="cover",type="string",description="课程封皮",default=null),
 *      @SWG\Property(property="status",type="string",enum={"draft","published","closed"},description="状态：未发布/已发布/已关闭",default="draft",readOnly=true),
 *      @SWG\Property(property="serialize_mode",type="string",enum={"none","serialized","finished"},description="连载状态：无/连载中/已完结",default="none"),
 *      @SWG\Property(property="reviews_count",type="integer",description="评价数",default=0,readOnly=true),
 *      @SWG\Property(property="rating",type="integer",format="float",maximum=5,minimum=0,description="评分",default=0,readOnly=true),
 *      @SWG\Property(property="notes_count",type="integer",description="笔记数",default=0,readOnly=true),
 *      @SWG\Property(property="students_count",type="integer",description="学生数",default=0,readOnly=true),
 *      @SWG\Property(property="is_recommended",type="bool",description="是否推荐",default=false),
 *      @SWG\Property(property="recommended_seq",type="integer",description="推荐序号",default=0),
 *      @SWG\Property(property="recommended_at",type="string",format="date-time",description="推荐时间",default=null),
 *      @SWG\Property(property="hit_count",type="integer",description="访问数",default=0,readOnly=true),
 *      @SWG\Property(property="copy_id",type="integer",description="复制（目前暂未用到）",default=null,readOnly=true),
 *      @SWG\Property(property="locked",type="bool",description="是否锁定",default=false),
 *      @SWG\Property(property="min_course_price",type="integer",description="最高价（系统根据版本价格维护）",default=0,readOnly=true),
 *      @SWG\Property(property="max_course_price",type="integer",description="最低价（系统根据版本价格维护）",default=0,readOnly=true),
 *      @SWG\Property(property="default_plan_id",type="integer",description="默认版本",readOnly=true),
 *      @SWG\Property(property="discount_id",type="integer",description="优惠活动ID",default=null,readOnly=true),
 *      @SWG\Property(property="discount",type="integer",description="折扣额度",default=0,readOnly=true),
 *      @SWG\Property(property="max_discount",type="integer",description="最大折扣（系统根据版本价格维护）",default=0,readOnly=true),
 *      @SWG\Property(property="materials_count",type="integer",description="资料数",default=0,readOnly=true),
 *      @SWG\Property(property="created_at",type="string",format="date-time",description="创建时间",readOnly=true),
 *      @SWG\Property(property="updated_at",type="string",format="date-time",description="更新时间",readOnly=true),
 *      @SWG\Property(property="deleted_at",type="string",format="date-time",description="删除时间",readOnly=true)
 * )
 * @SWG\Response(
 *      response="CoursePagination",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/Course")),
 *          @SWG\Property(property="meta",ref="$/definitions/MetaProperty")
 *      )
 * )
 *
 * // Fillable parameters 表单参数
 * @SWG\Parameter(parameter="CourseForm-title",name="title",required=true,in="formData",type="string",maxLength=191,description="课程标题")
 * @SWG\Parameter(parameter="CourseForm-subtitle",name="subtitle",in="formData",type="string",maxLength=191,default=null,description="课程副标题")
 * @SWG\Parameter(parameter="CourseForm-summary",name="summary",in="formData",type="string",default=null,description="课程简介")
 * @SWG\Parameter(parameter="CourseForm-category_id",name="category_id",in="formData",type="integer",description="课程分类")
 * @SWG\Parameter(parameter="CourseForm-goals",name="goals",in="formData",type="array",@SWG\Items(type="string"),description="课程目标")
 * @SWG\Parameter(parameter="CourseForm-audiences",name="audiences",in="formData",type="array",@SWG\Items(type="string"),description="课程受众")
 * @SWG\Parameter(parameter="CourseForm-tags",name="tags",in="formData",type="array",@SWG\Items(type="string"),description="课程标签")
 * @SWG\Parameter(parameter="CourseForm-cover",name="cover",in="formData",type="string",description="课程封皮")
 * @SWG\Parameter(parameter="CourseForm-status",name="status",in="formData",type="string",enum={"draft","published","closed"},description="状态：未发布/已发布/已关闭",default="draft")
 * @SWG\Parameter(parameter="CourseForm-serialize_mode",name="serialize_mode",in="formData",type="string",enum={"none","serialized","finished"},description="连载状态：无/连载中/已完结",default="none")
 * @SWG\Parameter(parameter="CourseForm-is_recommended",name="is_recommended",in="formData",type="boolean",default=false,description="是否推荐")
 * @SWG\Parameter(parameter="CourseForm-recommended_seq",name="recommended_seq",in="formData",type="integer",description="推荐系数")
 * @SWG\Parameter(parameter="CourseForm-locked",name="locked",in="formData",type="boolean",default=false,description="是否锁定（锁定后无法对其进行任何写操作）")
 *
 * // Searchable parameters 搜索参数
 * @SWG\Parameter(parameter="CourseQuery-title",name="title",in="query",type="string",description="名称")
 * @SWG\Parameter(parameter="CourseQuery-category_id",name="category_id",in="query",type="string",description="分类ID")
 * @SWG\Parameter(parameter="CourseQuery-category:name",name="category:name",in="query",type="string",description="分类名称")
 * @SWG\Parameter(parameter="CourseQuery-status",name="status",in="query",type="string",enum={"draft","published","closed"},description="状态：未发布/已发布/已关闭")
 * @SWG\Parameter(parameter="CourseQuery-serialize_mode",name="serialize_mode",in="query",type="string",enum={"none","serialized","finished"},description="连载状态：无/连载中/已完结")
 * @SWG\Parameter(parameter="CourseQuery-user_id",name="user_id",in="query",type="integer",description="创建人ID")
 * @SWG\Parameter(parameter="CourseQuery-user:username",name="user:username",in="query",type="integer",description="创建人姓名")
 * @SWG\Parameter(parameter="CourseQuery-is_recommended",name="is_recommended",in="query",type="boolean",description="是否推荐")
 * @SWG\Parameter(parameter="CourseQuery-recommended_seq",name="recommended_seq",in="query",type="integer",description="推荐系数 (降序排名)")
 * @SWG\Parameter(parameter="CourseQuery-recommended_at",name="recommended_at",in="query",type="string",description="推荐日期")
 * @SWG\Parameter(parameter="CourseQuery-min_course_price",name="min_course_price",in="query",type="integer",description="最小价格")
 * @SWG\Parameter(parameter="CourseQuery-max_course_price",name="max_course_price",in="query",type="integer",description="最大价格")
 * @SWG\Parameter(parameter="CourseQuery-reviews_count",name="reviews_count",in="query",type="integer",description="评价数")
 * @SWG\Parameter(parameter="CourseQuery-rating",name="rating",in="query",type="integer",description="评分数")
 * @SWG\Parameter(parameter="CourseQuery-notes_count",name="notes_count",in="query",type="integer",description="笔记数")
 * @SWG\Parameter(parameter="CourseQuery-students_count",name="students_count",in="query",type="integer",description="学员数")
 * @SWG\Parameter(parameter="CourseQuery-hit_count",name="hit_count",in="query",type="integer",description="点击次数")
 * @SWG\Parameter(parameter="CourseQuery-materials_count",name="materials_count",in="query",type="integer",description="材料数")
 *
 * // sortable parameters 排序参数
 * @SWG\Parameter(
 *     parameter="Course-sort",
 *     name="sort",
 *     in="query",
 *     type="string",
 *     description="使用方法见 sortable；[recommended_seq,reviews_count,rating,notes_count,students_count,hit_count,materials_count,min_course_price,max_course_price,created_at]，最新created_at,desc；最热students_count,desc；推荐recommended_seq,desc",
 * )
 *
 * // Include parameters 关联参数
 * @SWG\Parameter(
 *     parameter="Course-include",
 *     name="include",
 *     in="query",
 *     type="string",
 *     description="使用方法见 Include；{user:创建者,plans:版本,default_plan:默认版本,tags:标签,category:分类}",
 * )
 */
class Course extends Model
{
    use SoftDeletes, SearchableTrait, SortableTrait, NotCopyTrait, ModelHasStatusTrait, HashIdTrait;

    use Cachable;

    /**
     * @var string 课程
     */
    protected $table = 'courses';

    /**
     * @var array
     */
    public $sortable = [
        'recommended_seq',
        'reviews_count',
        'rating',
        'notes_count',
        'students_count',
        'hit_count',
        'materials_count',
        'min_course_price',
        'max_course_price',
        'created_at',
    ];

    /**
     * @var array
     */
    public $searchable = [
        'title',
        'category_id',
        'category:name',
        'status',
        'serialize_mode',
        'user_id',
        'user:username',
        'is_recommended',
        'recommended_at',
        'recommended_seq',
        'min_course_price',
        'max_course_price',
        'reviews_count',
        'rating',
        'notes_count',
        'students_count',
        'hit_count',
        'materials_count',
    ];

    /**
     * @var array
     */
    protected $casts = ['goals' => 'array', 'audiences' => 'array',];

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'subtitle',
        'summary',
        'category_id',
        'goals',
        'audiences',
        'cover',
        'serialize_mode',
    ];

    /**
     * @var array
     */
    public static $baseFields = [
        'id',
        'title',
        'cover',
        'rating',
        'status',
        'serialize_mode',
        'students_count',
        'reviews_count',
        'notes_count',
        'hit_count',
        'min_course_price',
        'max_course_price',
        'recommended_seq',
        'created_at'
    ];

    /**
     * 默认教学版本
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function default_plan()
    {
        return $this->hasOne(Plan::class, 'id', 'default_plan_id');
    }

    /**
     * 教学版本
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function plans()
    {
        return $this->hasMany(Plan::class);
    }

    /**
     * 分类
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
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
     * 课程标签
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'model', 'model_has_tags');
    }

    /**
     * 课程题目
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    /**
     * 课程考试
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tests()
    {
        return $this->hasMany(Test::class);
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
     * 是否收藏
     *
     * @return bool
     * @author 王凯
     */
    public function isFavourite()
    {
        if (!$me = auth('web')->user()) {
            return false;
        }

        return Favorite::where('user_id', $me->id)
            ->where('model_type', FavoriteType::COURSE)
            ->where('model_id', $this->id)
            ->exists();
    }

    /**
     * 是否可管理
     *
     * @return bool
     * @author 王凯
     */
    public function isControl()
    {
        if (!$me = auth('web')->user()) {
            return false;
        }

        if ($me->isAdmin()) {
            return true;
        }

//        return (bool)$me->manageCourses()->where('course_id', $this->id)->count();
        return $this->user_id == $me->id;
    }


    public function teachers()
    {
        return $this->hasMany(PlanTeacher::class, 'course_id', 'id');
    }
}
