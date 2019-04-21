<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;

/**
 * @SWG\Definition(
 *      definition="Log",
 *      type="object",
 *      required={},
 *      description="日志模型",
 *      @SWG\Property(property="id",type="string",readOnly=true),
 *      @SWG\Property(property="user_id",type="integer",description="用户I",readOnly=true),
 *      @SWG\Property(property="method",type="string",description="请求方式",readOnly=true),
 *      @SWG\Property(property="root",type="string",description="host",readOnly=true),
 *      @SWG\Property(property="path",type="string",description="uri",readOnly=true),
 *      @SWG\Property(property="url",type="string",description="",readOnly=true),
 *      @SWG\Property(property="full_url",type="string",description="",readOnly=true),
 *      @SWG\Property(property="ip",type="string",description="",readOnly=true),
 *      @SWG\Property(property="area",type="string",description="区域",readOnly=true),
 *      @SWG\Property(property="params",type="string",description="参数",readOnly=true),
 *      @SWG\Property(property="referer",type="string",description="来源",readOnly=true),
 *      @SWG\Property(property="user_agent",type="string",description="客户端代理",readOnly=true),
 *      @SWG\Property(property="device",type="string",description="设备",readOnly=true),
 *      @SWG\Property(property="browser",type="string",description="浏览器",readOnly=true),
 *      @SWG\Property(property="browser_version",type="string",description="浏览器版本",readOnly=true),
 *      @SWG\Property(property="platform",type="string",description="平台",readOnly=true),
 *      @SWG\Property(property="platform_version",type="string",description="平台版本",readOnly=true),
 *      @SWG\Property(property="is_mobile",type="bool",description="是否为手机",readOnly=true),
 *      @SWG\Property(property="request_headers",type="string",description="请求头",readOnly=true),
 *      @SWG\Property(property="request_time",type="string",format="date-time",description="请求时间",readOnly=true),
 *      @SWG\Property(property="status_code",type="integer",description="响应码",readOnly=true),
 *      @SWG\Property(property="response_content",type="string",description="响应内容",readOnly=true),
 *      @SWG\Property(property="response_headers",type="string",description="响应头",readOnly=true),
 *      @SWG\Property(property="response_time",type="string",format="date-time",description="响应时间",readOnly=true),
 *      @SWG\Property(property="created_at",type="string",format="date-time",description="创建时间",readOnly=true),
 *      @SWG\Property(property="updated_at",type="string",format="date-time",description="更新时间",readOnly=true)
 * )
 * @SWG\Response(
 *      response="LogPagination",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/Log")),
 *          @SWG\Property(property="meta",ref="$/definitions/MetaProperty")
 *      )
 * )
 * @SWG\Response(
 *      response="LogResponse",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/Log"))
 *      )
 * )
 * // Searchable parameters 搜索参数
 * @SWG\Parameter(parameter="LogQuery-user_id",name="user_id",in="query",type="integer",description="主动关注者ID")
 * @SWG\Parameter(parameter="LogQuery-user:username",name="user:username",in="query",type="string",description="主动关注者账户")
 * @SWG\Parameter(parameter="LogQuery-request_time",name="request_time",in="query",type="string",format="date-time",description="请求时间")
 * @SWG\Parameter(parameter="LogQuery-response_time",name="response_time",in="query",type="string",format="date-time",description="响应时间")
 * @SWG\Parameter(parameter="LogQuery-created_at",name="created_at",in="query",type="string",format="date-time",description="创建时间")
 *
 * // sortable parameters 排序参数
 * @SWG\Parameter(
 *     parameter="Log-sort",
 *     name="sort",
 *     in="query",
 *     type="string",
 *     description="使用方法见 sortable；[request_time,created_at]",
 * )
 *
 * // Include parameters 关联参数
 * @SWG\Parameter(
 *     parameter="Log-include",
 *     name="include",
 *     in="query",
 *     type="string",
 *     description="使用方法见 Include；{user:请求发起人}",
 * )
 */
class Log extends Model
{
    use SearchableTrait, SortableTrait;

    /**
     * @var string 日志表
     */
    protected $table = 'logs';

    /**
     * @var array
     */
    public $searchable = [
        'user_id',
        'user:username',
        'created_at',
        'request_time',
        'response_time',
    ];

    /**
     * @var array
     */
    public $sortable = ['request_time', 'created_at'];

    /**
     * @var string 数据库连接
     */
    protected $connection = 'log';

    /**
     * @var array
     */
    protected $casts = ['params' => 'array', 'request_headers' => 'array', 'response_content' => 'array', 'response_headers' => 'array',];

    /**
     * @var array
     */
    protected $dates = ['response_time', 'request_time',];

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'method',
        'root',
        'url',
        'full_url',
        'path',
        'ip',
        'area',
        'params',
        'referer',
        'user_agent',
        'device',
        'browser',
        'browser_version',
        'platform',
        'platform_version',
        'is_mobile',
        'request_headers',
        'request_time',
        'status_code',
        'response_content',
        'response_headers',
        'response_time',
    ];

    /**
     * @var string
     */
    public $defaultSortCriteria = 'created_at,desc';

    /**
     * 请求人
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * 登录用户
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Carbon\Carbon $start
     * @param \Carbon\Carbon $end
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLogin($query, Carbon $start, Carbon $end)
    {
        return $query->where(['method' => 'POST', 'path' => 'api/web/login', 'status_code' => '200'])
            ->where('user_id', '>', 0)
            ->where('created_at', '>', $start)->groupBy('user_id');
    }

    /**
     * 活跃用户
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Carbon\Carbon $start
     * @param \Carbon\Carbon $end
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query, $start, $end)
    {
        return $query->where('request_time', '>', $start)
            ->where('user_id', '>', 0)
            ->groupBy('user_id')
            ->select(\DB::raw('user_id, count(user_id) as request_count'))
            ->orderBy('request_count', 'desc');
    }

    /**
     * 获取最近一次手动发送通知的时间
     *
     * @return string|null
     */
    public static function lastSentNotificationTime()
    {
        // TODO 可以移除
        $carbon = Log::where('url', dingo_route('notification.store'))
            ->where('method', 'POST')
            ->where('status_code', 201)
            ->latest()->value('request_time');

        return $carbon ? $carbon->toDateTimeString() : null;
    }
}
