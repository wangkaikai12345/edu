<?php

namespace App\Http\Controllers\Backstage;

use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backstage\UserRequest;
use App\Http\Transformers\UserTransformer;
use App\Models\Log;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class TeacherController extends Controller
{
    /**
     * 教师列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        // 推荐序号大的老师默认排前
        $request->offsetSet('sort', 'recommended_seq,desc');

        $teachers = User::role(UserType::TEACHER)->filtered(array_filter($request->all()))->sorted()->paginate(self::perPage());

        return view('admin.teacher.index', compact('teachers'));
    }


    /**
     * 推荐教师列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function recommendIndex(Request $request)
    {
        // 推荐序号大的老师默认排前
        $request->offsetSet('sort', 'recommended_seq,desc');

        $teachers = User::role(UserType::TEACHER)
            ->filtered(array_filter($request->all()))
            ->sorted()->where('users.is_recommended', true)
            ->paginate(self::perPage());

        return view('admin.teacher.recommend_index', compact('teachers'));
    }


    /**
     * 添加教师
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.teacher.create');
    }


    /**
     * 推荐教师页面
     *
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function recommendShow(User $user)
    {
        !$user->hasRole(UserType::TEACHER) && $this->response->errorBadRequest(__('Only teacher can be recommended.'));

        return view('admin.teacher.recommend', compact('user'));
    }


    /**
     * 推荐/取消教师
     *
     * @param User $user
     * @return \Dingo\Api\Http\Response
     */
    public function recommend(User $user)
    {
        !$user->hasRole(UserType::TEACHER) && $this->response->errorBadRequest(__('Only teacher can be recommended.'));

        $isRecommended = (bool)request('is_recommended', true);
        $recommendedSeq = (int)request('recommended_seq', 0);

        $user->is_recommended = $isRecommended;
        $user->recommended_seq = $isRecommended ? $recommendedSeq : 0;
        $user->recommended_at = $isRecommended ? now() : null;
        $user->save();

        return $this->response->noContent();
    }


    /**
     * 活跃用户
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function active()
    {
        // 分钟数规定
        $subMinutes = 15;

        // 开始时间
        $start = now()->subMinutes($subMinutes);

        // 结束时间
        $end = now();

        // 用户名搜索
        $username = request('username');

        // 查询语句
        $query = Log::query()
            ->select(DB::raw('max(logs.id) as id, count(logs.user_id) as request_count, 
                         logs.user_id, logs.ip, logs.platform, logs.platform_version, logs.is_mobile,
                         logs.request_time, logs.device, logs.browser, logs.browser_version'
            ))
            ->whereBetween('logs.request_time', [$start, $end])
            ->where('logs.user_id', '!=', 0)
            ->groupBy('logs.user_id')
            ->orderBy('logs.created_at', 'desc')
            ->with('user');

        // 用户昵称不为空
        if (!empty($username)) {
            $query->leftJoin('users', 'logs.user_id', '=', 'users.id')
                ->where('users.username', 'like', "%{$username}%");
        }

        // 获取LOG
        $logs = $query->paginate(self::perPage());

        // 返回页面
        return view('admin.users.active', compact('logs', 'subMinutes'));
    }


    /**
     * 登录日志
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function loginLog(Request $request)
    {
        // 初始化变量
        $username = $request->input('username');

        // 开始时间
        if (!empty($start = $request->input('start'))) {
            $start = Carbon::parse($start);
        }

        // 结束时间
        if (!empty($end = $request->input('end'))) {
            $end = Carbon::parse($end)->addDay();
        }

        // 查询语句
        $query = Log::query()
            ->select(DB::raw('max(logs.id) as id, count(logs.user_id) as request_count, 
                         logs.user_id,  logs.area , logs.request_time'
            ))
            ->where('logs.method', 'POST')
            ->where('logs.url', route('login'))
            ->where('logs.status_code', '200')
            ->where('logs.user_id', '!=', 0)
            ->groupBy('logs.user_id')
            ->orderBy('logs.created_at', 'desc')
            ->with('user');

        // 昵称搜索
        if (!empty($username)) {
            $query->leftJoin('users', 'logs.user_id', '=', 'users.id')
                ->where('users.username', 'like', "%{$username}%");
        }

        // 时间存在
        if ($start && $end) {
            $query->whereBetween('logs.request_time', [$start, $end]);
        }

        $logs = $query->paginate(self::perPage());

        return view('admin.users.login_log', compact('logs'));
    }


    /**
     * 用户登录详细日志
     *
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function userLog(User $user)
    {
        // 查询语句
        $query = Log::query()
            ->where('method', 'POST')
            ->where('url', route('login'))
            ->where('status_code', '200')
            ->where('user_id', $user->id)
            ->with('user')
            ->orderBy('logs.created_at', 'desc');

        $logs = $query->paginate(self::perPage());

        return view('admin.users.user_login_log', compact('logs'));
    }


    /**
     * 验证唯一性
     *
     * @param Request $request
     * @return string
     */
    public function verifyFieldUniqueness(Request $request)
    {
        $this->validate($request, ['key' => 'required|string', 'user_id' => 'nullable|string']);

        // 获取传递的值
        if (empty($value = $request->input($request->input('key')))) {
            return json_encode(['valid' => true]);
        }

        // 构建查询语句
        $query = User::where($request->input('key'), $value);

        // 判断user_id是否存在
        if (!empty($user_id = $request->input('user_id'))) {
            $query->where('id', '<>', $user_id);
        }

        // 查询是否重复
        if ($query->exists()) {
            return json_encode(['valid' => false]);
        }

        // 验证通过
        return json_encode(['valid' => true]);
    }
}
