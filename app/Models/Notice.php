<?php

namespace App\Models;

use App\Traits\NoticeTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;

/**
 * @SWG\Definition(
 *      definition="Notice",
 *      type="object",
 *      required={},
 *      description="公告模型",
 *      @SWG\Property(property="id",type="string",readOnly=true),
 *      @SWG\Property(property="content",type="string",description="公告内容"),
 *      @SWG\Property(property="type",type="string",enum={"web","admin","plan"},default="web",description="官网公告、后台公告、版本公告"),
 *      @SWG\Property(property="started_at",type="string",format="date-time",description="开始日期"),
 *      @SWG\Property(property="ended_at",type="string",format="date-time",default=true,description="结束日期"),
 *      @SWG\Property(property="plan_id",type="integer",description="版本",readOnly=true),
 *      @SWG\Property(property="user_id",type="integer",description="创建者",readOnly=true),
 *      @SWG\Property(property="created_at",type="string",format="date-time",description="创建时间",readOnly=true),
 *      @SWG\Property(property="updated_at",type="string",format="date-time",description="更新时间",readOnly=true)
 * )
 * @SWG\Response(
 *      response="NoticePagination",
 *      description="ok",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/Notice")),
 *          @SWG\Property(property="meta",ref="$/definitions/MetaProperty")
 *      )
 * )
 * @SWG\Response(
 *      response="NoticeResponse",
 *      description="ok",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/Notice"))
 *      )
 * )
 *
 * // Fillable parameters 表单参数
 * @SWG\Parameter(parameter="NoticeForm-content",name="content",required=true,in="formData",type="string",description="公告内容")
 * @SWG\Parameter(parameter="NoticeForm-started_at",name="started_at",required=true,in="formData",type="string",format="date-time",description="开始日期")
 * @SWG\Parameter(parameter="NoticeForm-ended_at",name="ended_at",required=true,in="formData",type="string",format="date-time",description="结束日期")
 * @SWG\Parameter(parameter="NoticeForm-type",name="type",required=true,in="formData",type="string",enum={"web","admin","plan"},default="web",description="官网公告、后台公告、版本公告（仅能通过前台添加）")
 * @SWG\Parameter(parameter="NoticeForm-plan_id",name="plan_id",in="formData",type="integer",description="当类型为版本时，则此版本ID必须存在")
 *
 * // Searchable parameters 搜索参数
 * @SWG\Parameter(parameter="NoticeQuery-started_at",name="started_at",in="query",type="string",format="date-time",description="开始日期")
 * @SWG\Parameter(parameter="NoticeQuery-ended_at",name="ended_at",in="query",type="string",format="date-time",description="结束日期")
 * @SWG\Parameter(parameter="NoticeQuery-type",name="type",in="query",type="string",enum={"web","admin","plan"},default="web",description="官网公告、后台公告、版本公告")
 * @SWG\Parameter(parameter="NoticeQuery-user_id",name="user_id",in="query",type="string",description="创建人ID")
 * @SWG\Parameter(parameter="NoticeQuery-user:username",name="user:username",in="query",type="string",description="创建人账户")
 *
 * // sortable parameters 排序参数
 * @SWG\Parameter(
 *     parameter="Notice-sort",
 *     name="sort",
 *     in="query",
 *     type="string",
 *     description="使用方法见 sortable；[started_at,ended_at,created_at]",
 * )
 *
 * // Include parameters 关联参数
 * @SWG\Parameter(
 *     parameter="Notice-include",
 *     name="include",
 *     in="query",
 *     type="string",
 *     description="使用方法见 Include；{plan:版本}",
 * )
 */
class Notice extends Model
{
    use SoftDeletes, SearchableTrait, SortableTrait, NoticeTrait;

    /**
     * @var string
     */
    protected $table = 'notices';

    /**
     * @var array
     */
    public $searchable = ['type', 'started_at', 'ended_at', 'user_id', 'user:username'];

    /**
     * @var array
     */
    public $sortable = ['started_at', 'ended_at', 'created_at'];

    /**
     * @var array
     */
    protected $fillable = ['type', 'content', 'started_at', 'ended_at', 'plan_id'];

    /**
     * @var string
     */
    protected $defaultSortCriteria = 'created_at,desc';

    /**
     * @var array 基础字段
     */
    public static $baseFields = ['id', 'content'];

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
     * 版本
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
