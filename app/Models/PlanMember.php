<?php

namespace App\Models;

use App\Enums\StudentStatus;
use App\Traits\JoinTrait;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;

/**
 * @SWG\Definition(
 *      definition="PlanMember",
 *      type="object",
 *      required={},
 *      description="版本成员模型",
 *      @SWG\Property(property="id",type="string",readOnly=true),
 *      @SWG\Property(property="course_id",type="integer",description="课程",readOnly=true),
 *      @SWG\Property(property="plan_id",type="integer",description="版本",readOnly=true),
 *      @SWG\Property(property="user_id",type="integer",description="学员"),
 *      @SWG\Property(property="order_id",type="integer",description="订单"),
 *      @SWG\Property(property="deadline",type="string",format="date-time",description="学习有效期截止日期"),
 *      @SWG\Property(property="join_type",type="string",enum={"purchase","audition"},default="course",description="参加方式：购买课程、试听"),
 *      @SWG\Property(property="learned_count",type="integer",description="已学任务"),
 *      @SWG\Property(property="learned_compulsory_count",type="integer",description="已学习必修任务"),
 *      @SWG\Property(property="credit",type="string",description="获得学分"),
 *      @SWG\Property(property="notes_count",type="integer",description="笔记数"),
 *      @SWG\Property(property="note_last_updated_at",type="string",format="date-time",description="笔记最后更新时间"),
 *      @SWG\Property(property="is_finished",type="bool",description="是否完成"),
 *      @SWG\Property(property="finished_at",type="string",format="date-time",description="完成时间"),
 *      @SWG\Property(property="status",type="string",enum={"beginning","learning","finished","exited"},description="未开始/学习中/已完成/已退出"),
 *      @SWG\Property(property="remark",type="string",description="备注"),
 *      @SWG\Property(property="last_learned_at",type="string",format="date-time",description="最后学习时间"),
 *      @SWG\Property(property="created_at",type="string",format="date-time",description="创建时间",readOnly=true),
 *      @SWG\Property(property="updated_at",type="string",format="date-time",description="更新时间",readOnly=true)
 * )
 * @SWG\Response(
 *      response="PlanMemberPagination",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/PlanMember")),
 *          @SWG\Property(property="meta",ref="$/definitions/MetaProperty")
 *      )
 * )
 * @SWG\Response(
 *      response="PlanMemberResponse",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/PlanMember"))
 *      )
 * )
 *
 * // Searchable parameters 搜索参数
 * @SWG\Parameter(parameter="PlanMemberQuery-course_id",name="course_id",in="query",type="integer",description="课程ID")
 * @SWG\Parameter(parameter="PlanMemberQuery-course:title",name="course:title",in="query",type="string",description="课程名称")
 * @SWG\Parameter(parameter="PlanMemberQuery-plan_id",name="plan_id",in="query",type="integer",description="版本ID")
 * @SWG\Parameter(parameter="PlanMemberQuery-plan:title",name="plan:title",in="query",type="string",description="版本名称")
 * @SWG\Parameter(parameter="PlanMemberQuery-user_id",name="user_id",in="query",type="integer",description="用户ID")
 * @SWG\Parameter(parameter="PlanMemberQuery-user:username",name="user:username",in="query",type="string",description="用户名")
 * @SWG\Parameter(parameter="PlanMemberQuery-order_id",name="order_id",in="query",type="string",description="订单ID")
 * @SWG\Parameter(parameter="PlanMemberQuery-order:title",name="order_id",in="query",type="string",description="订单标题")
 * @SWG\Parameter(parameter="PlanMemberQuery-join_type",name="join_type",in="query",type="string",enum={"purchase","audition"},default="course",description="加入版本的方式：购买、试听")
 * @SWG\Parameter(parameter="PlanMemberQuery-deadline",name="deadline",in="query",type="string",description="学习截止日期")
 * @SWG\Parameter(parameter="PlanMemberQuery-remark",name="remark",in="query",type="string",description="信息备注")
 * @SWG\Parameter(parameter="PlanMemberQuery-learned_count",name="learned_count",in="query",type="integer",description="已学任务数")
 * @SWG\Parameter(parameter="PlanMemberQuery-learned_compulsory_count",name="learned_compulsory_count",in="query",type="integer",description="已学必修任务数")
 * @SWG\Parameter(parameter="PlanMemberQuery-notes_count",name="notes_count",in="query",type="integer",description="笔记数")
 * @SWG\Parameter(parameter="PlanMemberQuery-is_finished",name="is_finished",in="query",type="boolean",description="是否已完成本版本")
 * @SWG\Parameter(parameter="PlanMemberQuery-finished_at",name="finished_at",in="query",type="string",default="course",description="完成版本时间")
 * @SWG\Parameter(parameter="PlanMemberQuery-status",name="status",in="query",type="string",enum={"beginning","learning","finished","exited"},description="未开始/学习中/已完成/已退出")
 * @SWG\Parameter(parameter="PlanMemberQuery-last_learned_at",name="last_learned_at",in="query",type="string",description="最后学习时间")
 *
 * // sortable parameters 排序参数
 * @SWG\Parameter(
 *     parameter="PlanMember-sort",
 *     name="sort",
 *     in="query",
 *     type="string",
 *     description="使用方法见 sortable；[created_at,deadline,finished_at,last_learned_at]",
 * )
 * '',
 * // Include parameters 关联参数
 * @SWG\Parameter(
 *     parameter="PlanMember-include",
 *     name="include",
 *     in="query",
 *     type="string",
 *     description="使用方法见 Include；{course:课程,plan:版本,user:用户,order:订单}",
 * )
 */
class PlanMember extends Model
{
    use SoftDeletes, SearchableTrait, SortableTrait;
    use JoinTrait;
//    use Cachable;
    /**
     * @var string
     */
    protected $table = 'plan_members';

    /**
     * @var array
     */
    public $sortable = ['*'];

    /**
     * @var array
     */
    public $searchable = [
        'course_id',
        'course:title',
        'plan_id',
        'plan:title',
        'user_id',
        'user:username',
        'join_type',
        'order_id',
        'order:title',
        'deadline',
        'remark',
        'learned_count',
        'learned_compulsory_count',
        'notes_count',
        'is_finished',
        'finished_at',
        'status',
        'last_learned_at',
    ];

    /**
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @var array
     */
    protected $dates = [
        'deadline',
        'finished_at',
        'last_learned_at',
        'deleted_at',
    ];

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
        return $this->belongsTo(Plan::class)->withTrashed();
    }


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
     * 订单
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * 正常学员
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeNormal(Builder $query)
    {
        return $query->whereIn('status', [StudentStatus::BEGINNING, StudentStatus::LEARNING, StudentStatus::FINISHED]);
    }
}
