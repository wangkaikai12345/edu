<?php

namespace App\Models;

use App\Enums\UserType;
use App\Traits\HashIdTrait;
use App\Traits\ModelHasRecommendTrait;
use Carbon\Carbon;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use function GuzzleHttp\Psr7\str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Input;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Auth;

/**
 * @SWG\Definition(
 *      definition="User",
 *      type="object",
 *      required={},
 *      description="用户资源模型",
 *      @SWG\Property(property="id",type="string",readOnly=true),
 *      @SWG\Property(property="username",type="string",description="账户",minLength=2,maxLength=30,example="admin"),
 *      @SWG\Property(property="email",type="string",default=null,description="邮箱"),
 *      @SWG\Property(property="phone",type="string",default=null,description="手机"),
 *      @SWG\Property(property="avatar",type="string",default=null,description="头像"),
 *      @SWG\Property(property="signature",type="string",description="签名",default="null",example="特立独行"),
 *      @SWG\Property(property="is_email_verified",type="boolean",default="false",description="是否验证邮箱（系统维护）",readOnly=true),
 *      @SWG\Property(property="is_phone_verified",type="boolean",default="false",description="是否验证手机（系统维护）",readOnly=true),
 *      @SWG\Property(property="tags",type="string",default=null,description="标签数组"),
 *      @SWG\Property(property="registered_ip",type="string",default=null,description="注册IP",readOnly=true),
 *      @SWG\Property(property="registered_way",type="string",default=null,description="注册方式",readOnly=true),
 *      @SWG\Property(property="is_recommended",type="boolean",default=false,description="是否推荐",readOnly=true),
 *      @SWG\Property(property="recommended_seq",type="integer",default=0,description="推荐序号",readOnly=true),
 *      @SWG\Property(property="recommended_at",type="string",format="date-time",default=null,description="推荐时间",readOnly=true),
 *      @SWG\Property(property="locked",type="bool",default=false,description="是否被锁定",readOnly=true),
 *      @SWG\Property(property="locked_deadline",type="string",format="date-time",default=null,description="锁定截止日期",readOnly=true),
 *      @SWG\Property(property="password_error_times",type="integer",default=0,description="密码错误次数",readOnly=true),
 *      @SWG\Property(property="last_password_failed_at",type="string",format="date-time",default=null,description="最后一次密码错误时间",readOnly=true),
 *      @SWG\Property(property="last_logined_at",type="string",format="date-time",default=null,description="最近登录时间",readOnly=true),
 *      @SWG\Property(property="last_logined_ip",type="string",format="date-time",default=null,description="最近登录IP",readOnly=true),
 *      @SWG\Property(property="new_messages_count",type="integer",default=0,description="新私信数",readOnly=true),
 *      @SWG\Property(property="new_notifications_count",type="integer",default=0,description="新通知数",readOnly=true),
 *      @SWG\Property(property="invitation_code",type="string",default=null,description="邀请码",readOnly=true),
 *      @SWG\Property(property="remember_token",type="string",default=null,description="记住我Token",readOnly=true),
 *      @SWG\Property(property="created_at",type="string",format="date-time",description="创建时间",readOnly=true),
 *      @SWG\Property(property="updated_at",type="string",format="date-time",description="更新时间",readOnly=true)
 * )
 * @SWG\Response(
 *      response="UserPagination",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/User")),
 *          @SWG\Property(property="meta",ref="$/definitions/MetaProperty")
 *      )
 * )
 * @SWG\Response(
 *      response="UserResponse",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/User"))
 *      )
 * )
 *
 * // Searchable parameters 搜索参数
 * @SWG\Parameter(parameter="UserQuery-username",name="username",in="query",type="string")
 * @SWG\Parameter(parameter="UserQuery-email",name="email",in="query",type="string")
 * @SWG\Parameter(parameter="UserQuery-phone",name="phone",in="query",type="string")
 * @SWG\Parameter(parameter="UserQuery-is_email_verified",name="is_email_verified",in="query",type="boolean",description="是否验证邮箱")
 * @SWG\Parameter(parameter="UserQuery-is_phone_verified",name="is_phone_verified",in="query",type="boolean",description="是否验证手机")
 * @SWG\Parameter(parameter="UserQuery-registered_ip",name="registered_ip",in="query",type="string",description="注册IP")
 * @SWG\Parameter(parameter="UserQuery-registered_way",name="registered_way",in="query",type="string",description="注册方式")
 * @SWG\Parameter(parameter="UserQuery-is_recommended",name="is_recommended",in="query",type="boolean",description="是否推荐")
 * @SWG\Parameter(parameter="UserQuery-recommended_seq",name="recommended_seq",in="query",type="integer",description="推荐教师系数（降序）")
 * @SWG\Parameter(parameter="UserQuery-locked",name="locked",in="query",type="boolean",description="是否锁定")
 * @SWG\Parameter(parameter="UserQuery-last_logined_at",name="last_logined_at",in="query",type="string",format="date-time",description="最后一次登录时间")
 * @SWG\Parameter(parameter="UserQuery-invitation_code",name="invitation_code",in="query",type="string",description="邀请码")
 * @SWG\Parameter(parameter="UserQuery-created_at",name="created_at",in="query",type="string",description="注册时间")
 * @SWG\Parameter(parameter="UserQuery-roles:name",name="roles:name",in="query",type="string",enum={"super-admin","admin","teacher","student"},description="角色名称（英文）")
 * @SWG\Parameter(parameter="UserQuery-roles:title",name="roles:title",in="query",type="string",enum={"超管","管理","教师","学生"},description="角色名称（中文）")
 *
 * // sortable parameters 排序参数
 * @SWG\Parameter(
 *     parameter="User-sort",
 *     name="sort",
 *     in="query",
 *     type="string",
 *     description="使用方法见 sortable；[created_at,last_logined_at]",
 * )
 *
 * // Include parameters 关联参数
 * @SWG\Parameter(
 *     parameter="User-include",
 *     name="include",
 *     in="query",
 *     type="string",
 *     description="使用方法见 Include；{inviter:邀请人}",
 * )
 */
class User extends Authenticatable implements JWTSubject
{
    use Notifiable {
        notify as protected laravelNotify;
    }
    use HasRoles, SearchableTrait, SortableTrait, ModelHasRecommendTrait, HashIdTrait;

//    use Cachable;

    const LOGIN_WECHAT = 'login_wechat_';

    const WECHAT_FLAG = 'wechat_flag';

    const QR_URL = 'wechat_code_';

    const NEED_BIND = 'need_bind_';

    const NEED_BIND_USER = 'need_bind_info_';

    /**
     * @var string 用户表
     */
    protected $table = 'users';

    /**
     * @var array
     */
    protected $sortable = ['*'];

    /**
     * @var array
     */
    public $searchable = [
        'username',
        'email',
        'phone',
        'is_email_verified',
        'is_phone_verified',
        'registered_ip',
        'registered_way',
        'recommended_seq',
        'locked',
        'last_logined_at',
        'invitation_code',
        'created_at',
        'roles:name',
        'is_recommended',
    ];

    /**
     * @var string Guard (For Laravel-permission)
     */
    protected $guard_name = 'web';

    /**
     * @var array
     */
    protected $casts = [
        'tags' => 'array',
        'coin' => 'integer',
    ];

    /**
     * @var array
     */
    public static $baseFields = ['id', 'username', 'avatar', 'signature'];

    /**
     * @var array
     */
    protected $fillable = [
        'username',
        'email',
        'phone',
        'avatar',
        'password',
        'signature',
        'tags',
        'invitation_code',
        'inviter_id',
        'remember_token',
        'open_id',
        'union_id',
    ];

    /**
     * @var array
     */
    protected $dates = ['last_logined_at', 'last_password_failed_at', 'locked_deadline'];

    /**
     * @var array
     */
    protected $hidden = ['password', 'remember_token',];

    /**
     * @var string 默认排序规则s
     */
    public $defaultSortCriteria = 'created_at,desc';

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return ['key' => $this->makePasswordTag()];
    }

    /**
     * 消息通知
     *
     * @param \Illuminate\Notifications\Notification $notification
     * @return void
     */
    public function notify($notification, $welcome = false)
    {
        // 如果要通知的人是当前用户，就不必通知了！
        if ($this->id == auth('web')->id() && !$welcome) {
            return;
        }

        $this->increment('new_notifications_count');

        $this->laravelNotify($notification);
    }

    /**
     * 清空消息提醒
     *
     * @return void
     */
    public function markAsRead()
    {
        $this->new_notifications_count = 0;
        $this->save();
        $this->unreadNotifications->markAsRead();
    }

    /**
     * 详细信息
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id', 'id');
    }

    /**
     * 学习的课程
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'plan_members', 'user_id', 'course_id');
    }

    /**
     * 参加的版本
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function plans()
    {
        return $this->belongsToMany(Plan::class, 'plan_members', 'user_id', 'plan_id');
    }

    /**
     * 管理的课程
     */
    public function manageCourses()
    {
        return $this->belongsToMany(Course::class, 'plan_teachers', 'user_id', 'course_id');
    }

    /**
     * 管理的版本
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function managePlans()
    {
        return $this->belongsToMany(Plan::class, 'plan_teachers', 'user_id', 'plan_id');
    }

    /**
     * 创建的话题
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    /**
     * 创建的笔记
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    /**
     * 创建的作业
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function homeworks()
    {
        return $this->hasMany(HomeworkPost::class);
    }

    /**
     * 购买的订单
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * 创建的回复
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    /**
     * 粉丝
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fans()
    {
        return $this->hasMany(Follow::class, 'follow_id', 'id');
    }

    /**
     * 关注
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function followers()
    {
        return $this->hasMany(Follow::class, 'user_id', 'id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'recipient_id', 'id');
    }

    /**
     * 退款记录
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function refunds()
    {
        return $this->hasMany(Refund::class);
    }

    /**
     * 创建的充值额度
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recharging()
    {
        return $this->hasMany(Recharging::class);
    }

    /**
     * 邀请人
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function inviter()
    {
        return $this->belongsTo(User::class, 'inviter_id', 'id');
    }

    /**
     * 指定时间段内的用户
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param Carbon $start
     * @param Carbon $end
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNew($query, Carbon $start, Carbon $end)
    {
        return $query->whereBetween('created_at', [$start, $end]);
    }

    /**
     * 制作密码标记（用于记录用户密码修改后，清除之前所有已登录的Token信息）
     *
     * @return string
     */
    public function makePasswordTag()
    {
        return md5($this->getAuthPassword());
    }

    /**
     * 是否为超管
     *
     * @return bool
     */
    public function isSuperAdmin()
    {
        return $this->hasRole(UserType::SUPER_ADMIN);
    }

    /**
     * 是否为管理
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->hasRole(UserType::ADMIN) || $this->hasRole(UserType::SUPER_ADMIN);
    }

    /**
     * 是否为教师
     *
     * @return bool
     */
    public function isTeacher()
    {
        return $this->hasRole(UserType::TEACHER);
    }

    /**
     * 是否为学员、普通用户
     *
     * @return bool
     */
    public function isStudent()
    {
        return $this->hasRole(UserType::STUDENT);
    }

    /**
     * 当前用户是否关注此人
     *
     * @return bool
     * @author 王凯
     */
    public function isFollow()
    {
        if (auth('web')->id() == $this->id) return false;

        return Follow::where(['user_id' => auth('web')->id(), 'follow_id' => $this->id])->exists();
    }

    /**
     * Applies filters.
     *
     * @param Builder $builder query builder
     * @param array $query query parameters to use for search - Input::all() is used by default
     */
    public function scopeFiltered(Builder $builder, array $query = [])
    {
        $query = (array)($query ?: array_filter(Input::all()));
        $mode = $this->getQueryMode($query);
        $query = $this->filterNonSearchableParameters($query);
        $constraints = $this->getConstraints($builder, $query);

        $this->applyConstraints($builder, $constraints, $mode);
    }


    /**
     * open_id查询用户
     *
     * @param $query
     * @param $open_id
     * @return mixed
     */
    public function scopeWhereOpenid($query, $open_id)
    {
        return $query->where(compact('open_id'));
    }


    /**
     * 处理名称
     *
     * @param $username
     * @return string
     */
    public function formatWxUsername($username)
    {
        do {

            $mark = $this->newQuery()->where(compact('username'))->exists();

            if ($mark === false) {
                return $username;
            } else {
                $username = $username . '_' . str_random(6);
            }
        } while ($mark);
    }
}
