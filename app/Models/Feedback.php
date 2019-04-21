<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;

/**
 * @SWG\Definition(
 *      definition="Feedback",
 *      type="object",
 *      required={},
 *      description="反馈模型",
 *      @SWG\Property(property="id",type="string",readOnly=true),
 *      @SWG\Property(property="content",type="string",maximum=191,description="反馈内容"),
 *      @SWG\Property(property="email",type="integer",default=null,description="反馈用户邮箱"),
 *      @SWG\Property(property="wechat",type="integer",default=null,description="反馈用户微信"),
 *      @SWG\Property(property="qq",type="integer",default=null,description="反馈用户QQ"),
 *      @SWG\Property(property="user_id",type="integer",default=null,description="反馈用户",readOnly=true),
 *      @SWG\Property(property="is_solved",type="bool",default=false,description="是否解决",readOnly=true),
 *      @SWG\Property(property="is_replied",type="bool",default=false,description="是否回复",readOnly=true),
 *      @SWG\Property(property="created_at",type="string",format="date-time",description="创建时间",readOnly=true),
 *      @SWG\Property(property="updated_at",type="string",format="date-time",description="更新时间",readOnly=true)
 * )
 * @SWG\Response(
 *      response="FeedbackPagination",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/Feedback")),
 *          @SWG\Property(property="meta",ref="$/definitions/MetaProperty")
 *      )
 * )
 *
 * // Fillable parameters 表单参数
 * @SWG\Parameter(parameter="FeedbackForm-content",name="content",required=true,in="formData",type="string",description="反馈内容")
 * @SWG\Parameter(parameter="FeedbackForm-email",name="email",in="formData",type="string",minimum=0,maximum=100,description="邮箱")
 * @SWG\Parameter(parameter="FeedbackForm-wechat",name="wechat",in="formData",type="string",description="微信")
 * @SWG\Parameter(parameter="FeedbackForm-qq",name="qq",in="formData",type="string",description="QQ")
 * @SWG\Parameter(parameter="FeedbackForm-is_solved",name="is_solved",in="formData",type="boolean",description="是否解决")
 * @SWG\Parameter(parameter="FeedbackForm-is_replied",name="is_replied",in="formData",type="boolean",description="是否恢复")
 *
 * // Searchable parameters 搜索参数
 * @SWG\Parameter(parameter="FeedbackQuery-email",name="email",in="query",type="string",description="邮箱")
 * @SWG\Parameter(parameter="FeedbackQuery-wechat",name="wechat",in="query",type="string",description="微信")
 * @SWG\Parameter(parameter="FeedbackQuery-qq",name="qq",in="query",type="string",description="QQ")
 * @SWG\Parameter(parameter="FeedbackQuery-is_solved",name="is_solved",in="query",type="boolean",description="是否解决")
 * @SWG\Parameter(parameter="FeedbackQuery-is_replied",name="is_replied",in="query",type="boolean",description="是否回复")
 *
 * // sortable parameters 排序参数
 * @SWG\Parameter(
 *     parameter="Feedback-sort",
 *     name="sort",
 *     in="query",
 *     type="string",
 *     description="使用方法见 sortable；[created_at,updated_at]",
 * )
 */
class Feedback extends Model
{
    use SortableTrait, SearchableTrait;

    /**
     * @var string 反馈表
     */
    protected $table = 'feedback';

    /**
     * @var array
     */
    protected $fillable = ['wechat', 'email', 'qq', 'content'];

    /**
     * @var array
     */
    protected $sortable = ['created_at', 'updated_at'];

    /**
     * @var array
     */
    protected $searchable = ['qq', 'wechat', 'email', 'is_solved', 'is_replied'];

    /**
     * @var string
     */
    protected $defaultSortCriteria = 'created_at,desc';
}
