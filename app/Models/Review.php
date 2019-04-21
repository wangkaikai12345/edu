<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;

/**
 * @SWG\Definition(
 *      definition="Review",
 *      type="object",
 *      required={},
 *      description="评价模型",
 *      @SWG\Property(property="id",type="string",readOnly=true),
 *      @SWG\Property(property="user_id",type="integer",description="评价人",readOnly=true),
 *      @SWG\Property(property="course_id",type="integer",description="课程",readOnly=true),
 *      @SWG\Property(property="plan_id",type="integer",description="版本",readOnly=true),
 *      @SWG\Property(property="content",type="string",maxLength=191,description="内容"),
 *      @SWG\Property(property="rating",type="integer",description="评分"),
 *      @SWG\Property(property="created_at",type="string",format="date-time",description="创建时间",readOnly=true),
 *      @SWG\Property(property="updated_at",type="string",format="date-time",description="更新时间",readOnly=true)
 * )
 * @SWG\Response(
 *      response="ReviewPagination",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/Review")),
 *          @SWG\Property(property="meta",ref="$/definitions/MetaProperty")
 *      )
 * )
 * @SWG\Response(
 *      response="ReviewResponse",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/Review"))
 *      )
 * )
 *
 * // Fillable parameters 表单参数
 * @SWG\Parameter(parameter="ReviewForm-content",name="content",required=true,in="formData",type="string",description="评论内容")
 * @SWG\Parameter(parameter="ReviewForm-rating",name="rating",in="formData",type="string",minimum=0,maximum=5,description="评分")
 *
 * // Searchable parameters 搜索参数
 * @SWG\Parameter(parameter="ReviewQuery-user_id",name="user_id",in="query",type="integer",description="作者ID")
 * @SWG\Parameter(parameter="ReviewQuery-user:username",name="user:username",in="query",type="string",description="作者用户名")
 * @SWG\Parameter(parameter="ReviewQuery-course_id",name="course_id",in="query",type="integer",description="课程ID")
 * @SWG\Parameter(parameter="ReviewQuery-course:title",name="course:title",in="query",type="string",description="课程标题")
 * @SWG\Parameter(parameter="ReviewQuery-plan_id",name="plan_id",in="query",type="integer",description="版本ID")
 * @SWG\Parameter(parameter="ReviewQuery-plan:title",name="plan:title",in="query",type="string",description="版本标题")
 * @SWG\Parameter(parameter="ReviewQuery-created_at",name="created_at",in="query",type="string",description="创建时间")
 *
 * // sortable parameters 排序参数
 * @SWG\Parameter(
 *     parameter="Review-sort",
 *     name="sort",
 *     in="query",
 *     type="string",
 *     description="使用方法见 sortable；[created_at,rating]",
 * )
 *
 * // Include parameters 关联参数
 * @SWG\Parameter(
 *     parameter="Review-include",
 *     name="include",
 *     in="query",
 *     type="string",
 *     description="使用方法见 Include；{user:作者,plan:版本,course:课程}",
 * )
 */
class Review extends Model
{
    use SearchableTrait, SortableTrait;

//    use Cachable;
    /**
     * @var string 评价
     */
    protected $table = 'reviews';

    /**
     * @var array
     */
    public $sortable = ['rating', 'created_at'];

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
        'rating',
        'created_at',
    ];

    /**
     * @var array
     */
    protected $fillable = ['content', 'rating', 'user_id', 'plan_id', 'course_id'];

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @var string
     */
    public $defaultSortCriteria = 'created_at,desc';

    /**
     * @var array
     */
    public static $baseFields = ['id', 'content', 'rating'];

    /**
     * 评价者
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
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

    /**
     * 课程
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
