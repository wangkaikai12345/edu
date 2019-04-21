<?php

namespace App\Models;

use App\Enums\ExpiryMode;
use App\Enums\Status;
use App\Enums\TaskResultStatus;
use App\Traits\HashIdTrait;
use App\Traits\ModelHasStatusTrait;
use Carbon\Carbon;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;

/**
 * @SWG\Definition(
 *      definition="Plan",
 *      type="object",
 *      description="版本模型",
 *      @SWG\Property(property="id",type="string",readOnly=true),
 *      @SWG\Property(property="course_id",type="integer",description="课程",readOnly=true),
 *      @SWG\Property(property="course_title",type="integer",description="课程标题",readOnly=true),
 *      @SWG\Property(property="title",type="integer",description="版本名称",readOnly=true),
 *      @SWG\Property(property="about",type="integer",default=null,description="版本简介"),
 *      @SWG\Property(property="learn_mode",type="string",enum={"lock","free"},default="free",description="学习模式：解锁式/自由式"),
 *      @SWG\Property(property="expiry_mode",type="string",enum={"forever","period"},default="forever",description="学习有效期类型：永久有效、固定周期"),
 *      @SWG\Property(property="expiry_started_at",type="string",default=null,format="date-time",description="有效时长开始日期,当模式为 period 时，该参数必填"),
 *      @SWG\Property(property="expiry_ended_at",type="string",format="date-time",default=null,description="有效时长截止日期,当模式为 period 时，该参数必填"),
 *      @SWG\Property(property="expiry_days",type="integer",default=0,description="进入班级后的有效学习天数"),
 *      @SWG\Property(property="goals",type="string",default=null,description="版本目标"),
 *      @SWG\Property(property="audiences",type="string",default=null,description="受众群体"),
 *      @SWG\Property(property="is_default",type="bool",description="默认版本",default=false,readOnly=true),
 *      @SWG\Property(property="max_students_count",type="integer",default=0,description="最大学员数量"),
 *      @SWG\Property(property="status",type="string",enum={"draft","published","closed"},default="draft",description="状态",readOnly=true),
 *      @SWG\Property(property="user_id",type="integer",description="用户",readOnly=true),
 *      @SWG\Property(property="is_free",type="bool",default=false,description="是否免费"),
 *      @SWG\Property(property="free_started_at",type="string",format="date-time",description="免费开始时间"),
 *      @SWG\Property(property="free_ended_at",type="string",format="date-time",description="免费结束时间"),
 *      @SWG\Property(property="compulsory_tasks_count",type="integer",default=0,description="必修任务数",readOnly=true),
 *      @SWG\Property(property="tasks_count",type="integer",default=0,description="任务个数",readOnly=true),
 *      @SWG\Property(property="students_count",type="integer",default=0,description="学员个数",readOnly=true),
 *      @SWG\Property(property="notes_count",type="integer",default=0,description="笔记个数",readOnly=true),
 *      @SWG\Property(property="reviews_count",type="integer",default=0,description="评价个数",readOnly=true),
 *      @SWG\Property(property="rating",type="integer",default=0,description="评分",readOnly=true),
 *      @SWG\Property(property="hit_count",type="integer",default=0,description="访问数",readOnly=true),
 *      @SWG\Property(property="topics_count",type="integer",default=0,description="话题数",readOnly=true),
 *      @SWG\Property(property="services",type="string",description="服务细则"),
 *      @SWG\Property(property="show_services",type="bool",description="是否展示服务细则"),
 *      @SWG\Property(property="enable_finish",type="bool",description="允许直接完成任务"),
 *      @SWG\Property(property="income",type="integer",description="收入，单位：分",readOnly=true),
 *      @SWG\Property(property="price",type="integer",description="价格，单位：分"),
 *      @SWG\Property(property="origin_price",type="integer",description="原价格，单位：分"),
 *      @SWG\Property(property="coin_price",type="integer",description="虚拟币价格"),
 *      @SWG\Property(property="origin_coin_price",type="integer",description="原虚拟币价格"),
 *      @SWG\Property(property="locked",type="bool",default=false,description="是否锁定"),
 *      @SWG\Property(property="buy",type="bool",default=true,description="是否允许购买"),
 *      @SWG\Property(property="serialize_mode",type="string",enum={"none","serialized","finished"},default="none",description="无/连载中/已完结",readOnly=true),
 *      @SWG\Property(property="max_discount",type="integer",description="最大折扣",minimum=0,maximum=100,readOnly=true),
 *      @SWG\Property(property="copy_id",type="integer",default=0,description="复制",readOnly=true),
 *      @SWG\Property(property="deadline_notification",type="string",format="date-time",description="截止日期通知",readOnly=true),
 *      @SWG\Property(property="notify_before_days_of_deadline",type="integer",default=0,description="距离截止日期前多少日进行通知",readOnly=true),
 *      @SWG\Property(property="created_at",type="string",format="date-time",description="创建时间",readOnly=true),
 *      @SWG\Property(property="updated_at",type="string",format="date-time",description="更新时间",readOnly=true),
 * )
 *
 * @SWG\Response(
 *      response="PlanPagination",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/Plan")),
 *          @SWG\Property(property="meta",ref="#/definitions/MetaProperty")
 *      )
 * )
 * @SWG\Response(
 *      response="PlanResponse",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/Plan"))
 *      )
 * )
 *
 * // Fillable parameters 表单参数
 * @SWG\Parameter(parameter="PlanForm-title",name="title",in="formData",type="string",description="名称")
 * @SWG\Parameter(parameter="PlanForm-about",name="about",in="formData",type="string",description="介绍")
 * @SWG\Parameter(parameter="PlanForm-learn_mode",name="learn_mode",in="formData",type="string",enum={"lock","free"},default="free",description="学习模式：解锁式/自由式")
 * @SWG\Parameter(parameter="PlanForm-expiry_started_at",name="expiry_started_at",in="formData",type="string",format="date-time",description="名称")
 * @SWG\Parameter(parameter="PlanForm-expiry_ended_at",name="expiry_ended_at",in="formData",type="string",format="date-time",description="名称")
 * @SWG\Parameter(parameter="PlanForm-expiry_days",name="expiry_days",in="formData",type="integer",description="过期天数")
 * @SWG\Parameter(parameter="PlanForm-expiry_mode",name="expiry_mode",in="formData",type="string",enum={"forever","period"},default="forever",description="学习有效期类型：永久有效、固定周期")
 * @SWG\Parameter(parameter="PlanForm-goals",name="goals",in="formData",type="string",description="课程目标")
 * @SWG\Parameter(parameter="PlanForm-audiences",name="audiences",in="formData",type="string",description="适用人群")
 * @SWG\Parameter(parameter="PlanForm-max_students_count",name="max_students_count",in="formData",type="integer",description="最大容纳数")
 * @SWG\Parameter(parameter="PlanForm-is_free",name="is_free",in="formData",type="string",description="是否免费")
 * @SWG\Parameter(parameter="PlanForm-free_started_at",name="free_started_at",in="formData",type="string",description="免费开始时间")
 * @SWG\Parameter(parameter="PlanForm-free_ended_at",name="free_ended_at",in="formData",type="string",description="免费结束时间")
 * @SWG\Parameter(parameter="PlanForm-services",name="services",in="formData",type="string",description="相关服务：json 数组")
 * @SWG\Parameter(parameter="PlanForm-show_services",name="show_services",in="formData",type="boolean",description="是否展示服务信息")
 * @SWG\Parameter(parameter="PlanForm-enable_finish",name="enable_finish",in="formData",type="boolean",description="是否允许强制完成")
 * @SWG\Parameter(parameter="PlanForm-price",name="price",in="formData",type="integer",description="当前价格")
 * @SWG\Parameter(parameter="PlanForm-origin_price",name="origin_price",in="formData",type="integer",description="原价格")
 * @SWG\Parameter(parameter="PlanForm-coin_price",name="coin_price",in="formData",type="integer",description="当前虚拟币价格")
 * @SWG\Parameter(parameter="PlanForm-origin_coin_price",name="origin_coin_price",in="formData",type="string",description="原虚拟币价格")
 * @SWG\Parameter(parameter="PlanForm-locked",name="locked",in="formData",type="boolean",default=false,description="是否锁定，锁定后无法编辑")
 * @SWG\Parameter(parameter="PlanForm-buy",name="buy",in="formData",type="boolean",default=true,description="是否允许购买")
 * @SWG\Parameter(parameter="PlanForm-serialize_mode",in="formData",name="serialize_mode",type="string",enum={"none","serialized","finished"},description="无/连载中/已完结")
 * @SWG\Parameter(parameter="PlanForm-deadline_notification",name="deadline_notification",in="formData",type="boolean",default=false,description="是否开始截止日期通知")
 * @SWG\Parameter(parameter="PlanForm-notify_before_days_of_deadline",name="notify_before_days_of_deadline",in="formData",type="integer",default=1,description="举例截止日期多少天时进行通知")
 *
 * // sortable parameters 排序参数
 * @SWG\Parameter(
 *     parameter="Plan-sort",
 *     name="sort",
 *     in="query",
 *     type="string",
 *     description="使用方法见 sortable；[created_at]",
 * )
 * // Include parameters 关联参数
 * @SWG\Parameter(
 *     parameter="Plan-include",
 *     name="include",
 *     in="query",
 *     type="string",
 *     description="使用方法见 Include；{course:课程,chapters:章节,user:用户,members:成员,teachers:教师}",
 * )
 */
class Plan extends Model
{
    use SoftDeletes, SearchableTrait, SortableTrait, ModelHasStatusTrait, HashIdTrait;

    use Cachable;
    /**
     * @var string 版本
     */
    protected $table = 'plans';

    /**
     * @var array
     */
    public $sortable = ['*'];

    /**
     * @var array
     */
    protected $casts = [
        'goals' => 'array',
        'audiences' => 'array',
        'services' => 'array',
        'enable_finish' => 'boolean',
        'show_services' => 'boolean',
        'is_free' => 'boolean',
        'locked' => 'boolean',
        'buy' => 'boolean',
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'about',
        'expiry_started_at',
        'expiry_ended_at',
        'expiry_days',
        'expiry_mode',
        'goals',
        'audiences',
        'max_students_count',
        'is_free',
        'free_started_at',
        'free_ended_at',
        'services',
        'show_services',
        'enable_finish',
        'price',
        'origin_price',
        'coin_price',
        'origin_coin_price',
        'locked',
        'buy',
        'serialize_mode',
        'deadline_notification',
        'notify_before_days_of_deadline',
        'preview',
    ];

    /**
     * @var array
     */
    protected $dates = ['expiry_started_at', 'expiry_ended_at', 'free_started_at', 'free_ended_at', 'deleted_at'];

    /**
     * @var array
     */
    public $searchable = [
        'course_title',
        'title',
        'about',
        'learn_mode',
        'expiry_started_at',
        'expiry_ended_at',
        'goals',
        'audiences',
        'is_default',
        'status',
        'user_id',
        'is_free',
        'free_started_at',
        'free_ended_at',
        'tasks_count',
        'compulsory_tasks_count',
        'students_count',
        'notes_count',
        'hit_count',
        'topics_count',
        'price',
        'origin_price',
        'coin_price',
        'origin_coin_price',
        'locked',
        'serialize_mode',
    ];

    /**
     * @var array
     */
    public static $baseFields = ['id', 'title', 'course_id',];

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
     * 考试
     */
    public function tests()
    {
        return $this->hasMany(Test::class);
    }

    /**
     * 章节
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }

    /**
     * 话题
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

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
     * 成员
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function members()
    {
        return $this->hasMany(PlanMember::class);
    }

    /**
     * 笔记
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    /**
     * 评论
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * 老师
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function teachers()
    {
        return $this->hasMany(PlanTeacher::class);
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
     * 公告
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notices()
    {
        return $this->hasMany(Notice::class);
    }

    /**
     * 获取该版本已排序好的任务列表
     *
     * @return \Illuminate\Support\Collection|null
     */
    public function getSortedTasks()
    {
        /**
         * 层级关系：chapter 章 -> section 节 -> task 任务。
         * 排序规则：根据 seq 升序进行排序
         * 逻辑：
         * 1. 首先先排序章，其次排序章中的节，最后排序节中的任务，且任务必须为已发布状态。
         * 2. 仅需要任务列表，于是通过循环，留下仅需要的任务。并且维系已经排好的顺序。
         * 3. 二维数组变一维数组
         */
        $chapters = $this->chapters()
            ->where('parent_id', 0)->with(['children' => function ($query) {
                return $query->with(['tasks' => function ($query) {
                    return $query->where('status', Status::PUBLISHED)->orderBy('seq', 'asc');
                }])->orderBy('seq', 'asc');
            }])
            ->orderBy('seq', 'asc')->get();

        $tasks = collect();
        foreach ($chapters as $chapter) {
            foreach ($chapter->children as $section) {
                $taskContainer = $section->tasks;
                $taskContainer && $tasks->push($taskContainer);
            }
        }
        return $tasks->flatten(1);
    }

    /**
     * 获取当前学员正在学习的任务，或者下一个待学习的任务
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getLearningTask()
    {
        // 获取最近一次学
        $lastLearnTask = TaskResult::where('user_id', auth('web')->id())->where('plan_id', $this->id)->latest()->first();

        if (!$lastLearnTask) {
            // 查询本版本的第一任务
            $tasks = $this->getSortedTasks();
            return $tasks->first();
        }

        // 当前任务未完成则返回当前任务
        if ($lastLearnTask->status == TaskResultStatus::START) {
            return $lastLearnTask->task;
        }

        // 查询下一任务
        $lastTask = $lastLearnTask->task;
        $task = Task::where('chapter_id', $lastTask->chapter_id)
            ->where('seq', '>', $lastTask->seq)
            ->where('status', Status::PUBLISHED)
            ->oldest('seq')->first();
        if ($task) {
            return $task;
        }

        // 当无法读取时，查询已排序号的数据
        $tasks = $this->getSortedTasks();
        $index = 0;
        foreach ($tasks as $key => $value) {
            if ($value->id == $lastTask->id) {
                $index = $key;
                break;
            }
        }

        return $tasks[$index + 1] ?? null;
    }

    /**
     * 查询目录父子级
     *
     * @return \Illuminate\Database\Eloquent\Collection
     * @author 王凯
     */
    public function chapterChildren()
    {
        return $this->chapters()->where('parent_id', 0)->with(['children' => function($query){
            $query->with(['tasks' => function($query){
                $query->where('status', Status::PUBLISHED)->orderBy('seq');
            }])->orderBy('seq');
        }])->orderBy('seq')->get();

    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function chap()
    {
        return $this->chapters()->where('parent_id','<>', 0)->first();
    }

    /**
     * 获取不带任务的章节
     *
     * @return \Illuminate\Database\Eloquent\Collection
     * @author 王凯
     */
    public function chapterChildrenNotTask()
    {
        return $this->chapters()->where('parent_id', 0)->with(['children' => function($query){
            $query->orderBy('seq');
        }])->orderBy('seq')->get();

    }

    /**
     * 版本是否有效在时间内
     *
     * @return bool
     * @author 王凯
     */
    public function isValid()
    {
        if ($this->status != 'published') return false;
        // 是否锁定
        if ($this->locked) return false;

        // 有效期限
        if ($this->expiry_mode == ExpiryMode::FOREVER) return true;

        if ($this->expiry_mode == ExpiryMode::PERIOD && Carbon::now() > $this->expiry_ended_at) return false;

        if ($this->expiry_mode == ExpiryMode::VALID && Carbon::now() > $this->created_at->addDays($this->expiry_days)) return false;

        return true;
    }

    /**
     * 查询是否版本成员
     *
     * @param $userId
     * @return bool
     * @author 王凯
     */
    public function isMember()
    {
//        if (!$me = auth('web')->user()) {
//            return false;
//        }
//
//        if (auth('web')->user()->isAdmin()) return true;

//        // 是否为班级成员
//        $classrooms = ClassroomMember::where('user_id', $me->id)->normal()->pluck('classroom_id');
//        if ($classrooms->isEmpty()) {
//            return false;
//        }
//
//        // 是否为版本成员
//        if ($member = ClassroomCourse::whereIn('classroom_id', $classrooms->toArray())->where('plan_id', $this->id)->first()) {
//            return $member;
//        }
        if ($this->isControl()) {
            return true;
        }

        return PlanMember::where('user_id', auth('web')->id())->where('plan_id', $this->id)->normal()->first();
    }

    /**
     * 查询是否版本的教师
     *
     * @param $userId
     * @return bool
     * @author 王凯
     */
    public function isControl()
    {
        if (!$me = auth('web')->user()) {
            return false;
        }

        if ($me->isAdmin()) return true;

        if ($this->course->user_id == auth('web')->id()) return true;

        // 是否为班级老师
        $classrooms = ClassroomTeacher::where('user_id', $me->id)->pluck('classroom_id');
        if ($classrooms->isEmpty()) {
            return false;
        }

         if (ClassroomCourse::whereIn('classroom_id', $classrooms->toArray())->where('plan_id', $this->id)->exists()) {
             return true;
         }

        return PlanTeacher::where('plan_id', $this->id)->where('user_id', auth('web')->id())->count();
    }
}
