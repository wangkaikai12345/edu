<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Transformers\LogTransformer;
use App\Models\Log;
use App\Models\Note;
use App\Models\Order;
use App\Models\PlanMember;
use App\Models\Topic;
use App\Models\User;
use Carbon\Carbon;
use DB;

class StatController extends Controller
{
    /**
     * @SWG\Tag(name="admin/stat",description="后台统计相关接口")
     */

    /**
     * @SWG\Get(
     *  path="/admin/stats/today-count",
     *  tags={"admin/stat"},
     *  summary="今日统计计数",
     *  description="今日登录用户、今日活跃用户、今日新增用户、今日新增问答、今日新增笔记等",
     *  @SWG\Response(response=200,description="",@SWG\Schema(
     *      @SWG\Property(property="login_count",description="今日登录用户",example=20),
     *      @SWG\Property(property="active_count",description="今日活跃用户",example=10),
     *      @SWG\Property(property="user_count",description="今日新增用户",example=3),
     *      @SWG\Property(property="order_count",description="今日新增订单",example=30),
     *      @SWG\Property(property="topic_count",description="今日新增问答",example=50),
     *      @SWG\Property(property="note_count",description="今日新增笔记等",example=60),
     *   )),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function today(Log $log, User $user, Topic $topic, Note $note, Order $order)
    {
        $start = Carbon::today();
        $end = Carbon::today()->addDay(1);

        $activeCount = $log->active($start, $end)->get()->count();
        $loginCount = $log->login($start, $end)->count();
        $userCount = $user->new($start, $end)->count();
        $topicCount = $topic->new($start, $end)->count();
        $noteCount = $note->new($start, $end)->count();
        $orderCount = $order->new($start, $end)->count();

        return $this->response->array([
            'login_count' => $loginCount,
            'active_count' => $activeCount,
            'user_count' => $userCount,
            'topic_count' => $topicCount,
            'note_count' => $noteCount,
            'order_count' => $orderCount
        ]);
    }

    /**
     * @SWG\Get(
     *  path="/admin/stats/order",
     *  tags={"admin/stat"},
     *  summary="订单统计",
     *  description="",
     *  @SWG\Parameter(in="query",name="type",type="string",enum={"week","month"},default="week",description="类型：周、月"),
     *  @SWG\Response(response=200,description="",@SWG\Schema(
     *      @SWG\Property(property="pay_orders",type="array",description="付费订单",@SWG\Items(type="object")),
     *      @SWG\Property(property="free_orders",type="array",description="免费订单",@SWG\Items(type="object")),
     *  )),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function order(Order $order)
    {
        // 新增订单、付费订单
        switch (request('type')) {
            case 'month':
                $start = today()->startOfMonth();
                $end = today()->endOfMonth();
                $payOrders = $order->whereBetween('created_at', [$start, $end])
                    ->where('pay_amount', '>', 0)->groupBy('date')
                    ->get([DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as value')])
                    ->toArray();
                $freeOrders = $order->whereBetween('created_at', [$start, $end])
                    ->where('pay_amount', '=', 0)->groupBy('date')
                    ->get([DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as value')])
                    ->toArray();
                break;
            case 'week':
            default:
                $start = today()->startOfWeek();
                $end = today()->endOfWeek();
                $payOrders = $order->whereBetween('created_at', [$start, $end])
                    ->where('pay_amount', '>', 0)->groupBy('date')
                    ->get([DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as value')])
                    ->toArray();
                $freeOrders = $order->whereBetween('created_at', [$start, $end])
                    ->where('pay_amount', '=', 0)->groupBy('date')
                    ->get([DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as value')])
                    ->toArray();
                break;
        }

        $payOrders = $this->fillDate($payOrders);
        $freeOrders = $this->fillDate($freeOrders);

        return $this->response->array([
            'pay_orders' => $payOrders,
            'free_orders' => $freeOrders
        ]);
    }

    /**
     * @SWG\Get(
     *  path="/admin/stats/member",
     *  tags={"admin/stat"},
     *  summary="新增学员统计",
     *  description="",
     *  @SWG\Parameter(in="query",name="type",type="string",enum={"week","month"},default="week",description="类型：周、月"),
     *  @SWG\Response(response=200,description="数组格式数据"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function member(PlanMember $member)
    {
        switch (request('type')) {
            case 'month':
                $start = today()->startOfMonth();
                $end = today()->endOfMonth();
                $data = $member->whereBetween('created_at', [$start, $end])
                    ->groupBy('course_id')
                    ->with(['course' => function ($query) {
                        return $query->withTrashed()->select(['id', 'title', 'deleted_at']);
                    }])
                    ->orderBy('value', 'desc')
                    ->get(['course_id', DB::raw('COUNT(*) as value')])
                    ->toArray();
                break;
            case 'week':
            default:
                $start = today()->startOfWeek();
                $end = today()->endOfWeek();
                $data = $member->whereBetween('created_at', [$start, $end])
                    ->with(['course' => function ($query) {
                        return $query->withTrashed()->select(['id', 'title', 'deleted_at']);
                    }])
                    ->groupBy('course_id')
                    ->orderBy('value', 'desc')
                    ->get(['course_id', DB::raw('COUNT(*) as value')])
                    ->toArray();
                break;
        }
        return $this->response->array(compact('data'));
    }

    /**
     * @SWG\Get(
     *  path="/admin/stats/active-users",
     *  tags={"admin/stat"},
     *  summary="活跃用户列表",
     *  description="会搜索到前 15 分钟以内的活跃的用户",
     *  @SWG\Parameter(in="query",name="username",type="string",description="用户名"),
     *  @SWG\Parameter(ref="#/parameters/per_page"),
     *  @SWG\Parameter(ref="#/parameters/page"),
     *  @SWG\Response(response=200,description="ok"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function active(Log $log)
    {
        $start = now()->subMinutes(15);

        $end = now();

        // 用户名搜索
        $username = request('username');
        if ($username) {
            $logs = $log->whereBetween('logs.request_time', [$start, $end])
                ->leftJoin('users', 'logs.user_id', '=', 'users.id')
                ->where('users.username', 'like', "%{$username}%")
                ->where('logs.user_id', '!=', 0)
                ->orderByDesc('logs.request_time')
                ->select(['logs.id', 'logs.user_id'])
                ->get();

            $logsGroups = $logs->groupBy('user_id');

            $count = $logsGroups->count();

            $logIds = $logsGroups->map(function ($item) {
                return $item->first()->id;
            });

            $logs = Log::whereIn('id', $logIds)->paginate(self::perPage());
        } else {
            $count = $log->active($start, $end)->get()->count();

            $logs = $log->active($start, $end)->paginate(self::perPage());
        }

        foreach ($logs as $index => $item) {
            $log = Log::where('user_id', $item['user_id'])->orderBy('created_at', 'desc')->first([
                'id', 'ip', 'request_time', 'device', 'browser', 'browser_version', 'platform', 'platform_version', 'is_mobile',
            ]);

            $item->id = $log->id;
            $item->ip = $log->ip;
            $item->request_time = $log->request_time;
            $item->device = $log->device;
            $item->browser = $log->browser;
            $item->browser_version = $log->browser_version;
            $item->platform = $log->platform;
            $item->platform_version = $log->platform_version;
            $item->is_mobile = $log->is_mobile;
        }

        return $this->response->paginator($logs, new LogTransformer())->setMeta(['active_count' => $count]);
    }

    /**
     * 填充不完全的日期数据
     *
     * @param array $data
     * @return array
     */
    private function fillDate(array $data)
    {
        $container = [];
        foreach ($data as $index => $item) {
            // 判断当前日期与下一个日期
            if (isset($data[$index + 1])) {
                $current = Carbon::createFromFormat('Y-m-d', $item['date'])->startOfDay();
                $next = Carbon::createFromFormat('Y-m-d', $data[$index + 1]['date'])->startOfDay();
                $days = $current->diffInDays($next);
            } else {
                $current = Carbon::createFromFormat('Y-m-d', $item['date'])->startOfDay();
                $next = null;
                $days = 0;
            }

            $container[] = $item;

            // 填充缺少日期
            for ($i = 1; $i < $days; $i++) {
                // 生成指定时间
                $targetDate = $current->addDay()->format('Y-m-d');
                $container[] = ['date' => $targetDate, 'value' => 0];
            }
        }
        return $container;
    }
}