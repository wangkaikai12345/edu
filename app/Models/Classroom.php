<?php

namespace App\Models;

use App\Traits\HashIdTrait;
use App\Traits\ModelHasRecommendTrait;
use App\Traits\ModelHasStatusTrait;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;

/**
 * @SWG\Definition(
 *     definition="Classroom",
 *     type="object",
 *     required={"title","expiry_mode"},
 *     description="班级",
 *     @SWG\Property(property="id",type="integer",readOnly=true),
 *     @SWG\Property(property="title",type="string",maxLength=32,description="班级名称"),
 *     @SWG\Property(property="description",type="string",description="班级描述信息",default=null),
 *     @SWG\Property(property="status",type="string",enum={"draft","published","closed"},description="未发布/已发布/已关闭",default="draft"),
 *     @SWG\Property(property="expiry_mode",type="string",enum={"period","valid","forever"},description="时间范围/有效天数/永久有效"),
 *     @SWG\Property(property="expiry_started_at",type="string",description="开始时间",default=null),
 *     @SWG\Property(property="expiry_ended_at",type="string",description="截止时间",default=null),
 *     @SWG\Property(property="expiry_days",type="integer",description="自加入后的有效天数",default=0),
 *     @SWG\Property(property="category_id",type="integer",description="分类ID",default=0),
 *     @SWG\Property(property="origin_price",type="integer",description="原价格",default=0),
 *     @SWG\Property(property="price",type="integer",description="当前价格",default=0),
 *     @SWG\Property(property="cover",type="string",description="封面",default=null),
 *     @SWG\Property(property="domain",type="string",description="七牛域名",default=null),
 *     @SWG\Property(property="is_recommended",type="boolean",description="是否推荐",default=false),
 *     @SWG\Property(property="recommended_at",type="string",description="推荐时间",default=null),
 *     @SWG\Property(property="recommended_seq",type="string",description="推荐序号",default=0),
 *     @SWG\Property(property="members_count",type="integer",description="成员个数",default=0),
 *     @SWG\Property(property="courses_count",type="integer",description="课程个数",default=0),
 *     @SWG\Property(property="user_id",type="integer",description="创建人"),
 *     @SWG\Property(property="created_at",type="string",format="date-time",description="创建时间",readOnly=true),
 *     @SWG\Property(property="updated_at",type="string",format="date-time",description="更新时间",readOnly=true)
 * )
 *
 * // 分页响应
 * @SWG\Response(
 *   response="ClassroomPagination",
 *   description="",
 *   @SWG\Schema(
 *     @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/Classroom")),
 *     @SWG\Property(property="meta",ref="$/definitions/MetaProperty")
 *   )
 * )
 *
 * // 基础响应
 * @SWG\Response(
 *   response="ClassroomResponse",
 *   description="",
 *   @SWG\Schema(
 *     @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/Classroom")),
 *   )
 * )
 *
 * // Fillable parameters 表单参数
 * @SWG\Parameter(parameter="ClassroomForm-title",name="title",required=true,in="formData",type="string",maxLength=32,description="名称")
 * @SWG\Parameter(parameter="ClassroomForm-description",name="description",in="formData",type="string",description="简介")
 * @SWG\Parameter(parameter="ClassroomForm-expiry_mode",name="expiry_mode",required=true,in="formData",type="string",enum={"period","valid","forever"},description="时间范围/有效天数/永久有效")
 * @SWG\Parameter(parameter="ClassroomForm-expiry_started_at",name="expiry_started_at",in="formData",type="string",format="date-time",description="开始时间",default=null)
 * @SWG\Parameter(parameter="ClassroomForm-expiry_ended_at",name="expiry_ended_at",in="formData",type="string",format="date-time",description="结束时间",default=null)
 * @SWG\Parameter(parameter="ClassroomForm-expiry_days",name="expiry_days",in="formData",type="integer",description="有效天数",default=0)
 * @SWG\Parameter(parameter="ClassroomForm-category_id",name="category_id",in="formData",type="integer",description="分类ID",default=null)
 * @SWG\Parameter(parameter="ClassroomForm-price",name="price",in="formData",type="integer",description="当前价格",default=0)
 * @SWG\Parameter(parameter="ClassroomForm-origin_price",name="origin_price",in="formData",type="integer",description="原价格",default=0)
 * @SWG\Parameter(parameter="ClassroomForm-cover",name="cover",in="formData",type="string",description="封面",default=null)
 * @SWG\Parameter(parameter="ClassroomForm-status",name="status",in="formData",type="string",enum={"published","closed"},description="发布/取消",default="published")
 * @SWG\Parameter(parameter="ClassroomForm-is_recommended",name="is_recommended",in="formData",type="boolean",description="发布/取消",default=true)
 * @SWG\Parameter(parameter="ClassroomForm-recommended_seq",name="recommended_seq",in="formData",type="boolean",description="推荐序号",default=0)
 *
 * // Searchable parameters 搜索参数
 * @SWG\Parameter(parameter="ClassroomQuery-title",name="title",in="query",type="string",description="名称")
 * @SWG\Parameter(parameter="ClassroomQuery-status",name="title",in="query",type="string",enum={"draft","published","closed"},description="未发布/已发布/已关闭")
 * @SWG\Parameter(parameter="ClassroomQuery-expiry_mode",name="expiry_mode",in="query",type="string",description="有效类型")
 * @SWG\Parameter(parameter="ClassroomQuery-expiry_started_at",name="expiry_started_at",in="query",type="integer",description="开始时间")
 * @SWG\Parameter(parameter="ClassroomQuery-expiry_ended_at",name="expiry_ended_at",in="query",type="string",description="截止时间")
 * @SWG\Parameter(parameter="ClassroomQuery-category_id",name="category_id",in="query",type="string",description="分类")
 * @SWG\Parameter(parameter="ClassroomQuery-price",name="price",in="query",type="string",description="当前价格")
 * @SWG\Parameter(parameter="ClassroomQuery-origin_price",name="origin_price",in="query",type="string",description="原价格")
 * @SWG\Parameter(parameter="ClassroomQuery-is_recommended",name="is_recommended",in="query",type="boolean",description="是否为推荐")
 * @SWG\Parameter(parameter="ClassroomQuery-created_at",name="created_at",in="query",type="string",description="创建时间")
 *
 * // sortable parameters 排序参数
 * @SWG\Parameter(
 *     parameter="Classroom-sort",
 *     name="sort",
 *     in="query",
 *     type="string",
 *     description="使用方法见 sortable；['created_at', 'members_count', 'recommended_seq', 'price', 'expiry_started_at']，最新created_at,desc；最热members_count,desc；推荐recommended_seq,desc",
 * )
 *
 * // Include parameters 关联参数
 * @SWG\Parameter(
 *     parameter="Classroom-include",
 *     name="include",
 *     in="query",
 *     type="string",
 *     description="使用方法见 Include；{user:创建者,category:分类,courses:课程,plans:版本,members:学员,heads:班主任,teachers:教师,assistants:助教}",
 * )
 */
class Classroom extends Model
{
    use SearchableTrait, SortableTrait, ModelHasStatusTrait, ModelHasRecommendTrait, HashIdTrait;
//    use Cachable;
    /**
     * @var string
     */
    protected $table = 'classrooms';

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'expiry_mode',
        'expiry_started_at',
        'expiry_ended_at',
        'expiry_days',
        'category_id',
        'price',
        'origin_price',
        'cover',
        'preview',
        'is_buy',
        'is_show',
        'learn_mode'
    ];
    /**
     * @var array
     */
    public $searchable = [
        'title',
        'status',
        'expiry_mode',
        'expiry_started_at',
        'expiry_ended_at',
        'category_id',
        'price',
        'origin_price',
        'created_at',
        'is_recommended',
    ];
    /**
     * @var array
     */
    public $sortable = ['created_at', 'members_count', 'recommended_seq', 'price', 'expiry_started_at'];
    /**
     * @var string Searchable 默认排序
     */
    protected $defaultSortCriteria = 'created_at,desc';
    /**
     * @var array
     */
    protected $dates = ['expiry_started_at', 'expiry_ended_at', 'recommended_at'];

    protected $casts = [
        'services' => 'array'
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
     * 创建人
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * 课程
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'classroom_courses', 'classroom_id', 'course_id', 'id', 'id')
            ->withPivot(['is_synced']);
    }

    /**
     * 版本
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function plans()
    {
        return $this->belongsToMany(Plan::class, 'classroom_courses', 'classroom_id', 'plan_id', 'id', 'id')
            ->orderBy('seq')
            ->withPivot(['is_synced']);
    }

    /**
     * 获取班级的所有阶段
     *
     * @return \Illuminate\Support\Collection
     * @author 王凯
     */
    public function chapters()
    {
        $plans =  $this->plans;

        $collect = collect();

        foreach ($plans as $plan) {

            $chapters = $plan->chapters()->where('parent_id', 0)->orderBy('seq')->get();

            if ($chapters->count()) {
                foreach ($chapters as $chapter) {
                    $chapter && $collect->push($chapter);
                }
            }

        }

       return $collect;
    }

    /**
     * 获取班级的所有关
     *
     * @return \Illuminate\Support\Collection
     * @author 王凯
     */
    public function chaps()
    {
        $plans =  $this->plans;

        $collect = collect();

        foreach ($plans as $plan) {

            $chapters = $plan->chapters()->where('parent_id', '<>', 0)->orderBy('seq')->get();

            if ($chapters->count()) {
                foreach ($chapters as $chapter) {
                    $chapter && $collect->push($chapter);
                }
            }

        }

        return $collect;
    }

    /**
     * 获取班级的阶段和关
     *
     * @return \Illuminate\Support\Collection
     * @author 王凯
     */
    public function chaptersChildren()
    {
        $plans =  $this->plans;

        $collect = collect();

        foreach ($plans as $plan) {

            $chapters = $plan->chapters()
                ->where('parent_id', 0)
                ->with(['children' => function($query){
                        $query->orderBy('seq');
                    }])->orderBy('seq')->get();

            if ($chapters->count()) {
                foreach ($chapters as $chapter) {
                    $chapter && $collect->push($chapter);
                }
            }

        }

        return $collect;
    }

    /**
     * 班级标签
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'model', 'model_has_tags');
    }

    /**
     * 订单
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function orders()
    {
        return $this->morphMany(Order::class, 'product');
    }

    /**
     * 课程
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function members()
    {
        return $this->belongsToMany(User::class, 'classroom_members', 'classroom_id', 'user_id')
            ->withPivot([
                'remark',
                'deadline',
                'type',
                'status',
                'learned_count',
                'learned_compulsory_count',
                'finished_at',
                'exited_at',
                'last_learned_at',
                'created_at',
            ]);
    }

    /**
     * 班主任
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function heads()
    {
        return $this->belongsToMany(User::class, 'classroom_teachers', 'classroom_id', 'user_id')
            ->where('classroom_teachers.type', \App\Enums\TeacherType::HEAD);
    }

    /**
     * 教师
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function teachers()
    {
        return $this->belongsToMany(User::class, 'classroom_teachers', 'classroom_id', 'user_id')
            ->where('classroom_teachers.type', \App\Enums\TeacherType::TEACHER);
    }

    /**
     * 助教
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function assistants()
    {
        return $this->belongsToMany(User::class, 'classroom_teachers', 'classroom_id', 'user_id')
            ->where('classroom_teachers.type', \App\Enums\TeacherType::ASSISTANT);
    }

    /**
     *
     *
     * @return bool
     * @author 王凯
     */
    public function isControl()
    {
        if(auth('web')->id() == $this->user_id) return true;

        if(ClassroomTeacher::where(['user_id'=> auth('web')->id() , 'classroom_id' => $this->id])->exists());

        return false;
    }


    public function isMember()
    {
        if ($this->isControl()) {
            return true;
        }
        return ClassroomMember::where(['user_id'=> auth('web')->id() , 'classroom_id' => $this->id])->with('user')->first();
    }
}
