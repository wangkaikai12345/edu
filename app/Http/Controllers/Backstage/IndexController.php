<?php

namespace App\Http\Controllers\Backstage;

use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Models\Note;
use App\Models\Order;
use App\Models\PlanMember;
use App\Models\Topic;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    // 后台首页数据
    public function index(Request $request)
    {
        $dateType = $request->input('date_type', 'week');

        // 获取订单查询数据
        list($payOrdersDate, $payOrdersValue, $freeOrdersDate, $freeOrdersValue) = $this->order($dateType);

        // 今日数据
        $todayCount = $this->today();

        //课程排行
        $ranking = $this->member($dateType);


        // 返回查询数据
        return view('admin.index.index',
            compact(
                'payOrdersDate',
                'payOrdersValue',
                'freeOrdersDate',
                'freeOrdersValue',
                'todayCount',
                'ranking'
            ));
    }


    /**
     * 今日登录用户、今日活跃用户、今日新增用户、今日新增问答、今日新增笔记等
     *
     * @return array
     */
    public function today()
    {
        $start = Carbon::today();
        $end = Carbon::today()->addDay(1);

        $activeCount = Log::active($start, $end)->get()->count();
        $loginCount = Log::login($start, $end)->count();
        $userCount = User::new($start, $end)->count();
        $topicCount = Topic::new($start, $end)->count();
        $noteCount = Note::new($start, $end)->count();
        $orderCount = Order::new($start, $end)->count();

        return array(
            'login_count' => $loginCount,
            'active_count' => $activeCount,
            'user_count' => $userCount,
            'topic_count' => $topicCount,
            'note_count' => $noteCount,
            'order_count' => $orderCount
        );
    }


    /**
     * 获取订单统计
     *
     * @param $type
     * @return mixed
     */
    protected function order($type)
    {
        // 新增订单、付费订单
        switch ($type) {
            case 'month':
                $start = today()->startOfMonth()->format('Y-m-d');
                $end = today()->addDay()->subSecond()->format('Y-m-d');
                break;
            case 'week':
            default:
                $start = today()->subWeek()->format('Y-m-d');
                $end = today()->format('Y-m-d');
                break;
        }

        // 构建子查询语句
        $payOrdersQuery = Order::query()->newQuery()->whereBetween(DB::raw('DATE(created_at)'), [$start, $end])
            ->where('pay_amount', '>', 0)
            ->select([DB::raw('DATE(created_at) as  date'), DB::raw('COUNT(*) order_count')])
            ->groupBy(DB::raw('DATE(created_at)'));



        // 获取查询数据
        $payOrders = DB::table('calendar')
            ->newQuery()
            ->from(DB::raw("({$payOrdersQuery->toSql()}) as t"))
            ->mergeBindings($payOrdersQuery->getQuery())
            ->whereBetween('calendar.datelist', [$start, $end])
            ->select(DB::raw("calendar.datelist AS date, IF (order_count IS NULL, 0, order_count ) AS num"))
            ->rightJoin('calendar', 't.date', '=', 'calendar.datelist')
            ->orderBy('calendar.datelist', 'asc')
            ->get();

        // 构建子查询语句
        $freeOrdersQuery = Order::query()->newQuery()->whereBetween(DB::raw('DATE(created_at)'), [$start, $end])
            ->where('pay_amount', '=', 0)
            ->select([DB::raw('DATE(created_at) as  date'), DB::raw('COUNT(*) order_count')])
            ->groupBy(DB::raw('DATE(created_at)'));

        // 获取查询数据
        $freeOrders = DB::table('calendar')
            ->newQuery()
            ->from(DB::raw("({$freeOrdersQuery->toSql()}) as t"))
            ->mergeBindings($freeOrdersQuery->getQuery())
            ->whereBetween('calendar.datelist', [$start, $end])
            ->select(DB::raw("calendar.datelist AS date, IF (order_count IS NULL, 0, order_count ) AS num"))
            ->rightJoin('calendar', 't.date', '=', 'calendar.datelist')
            ->orderBy('calendar.datelist', 'asc')
            ->get();


        // 折线图
        $payOrdersDate = $freeOrdersDate = $payOrdersValue = $freeOrdersValue = [];


        foreach ($payOrders as $payOrder) {
            $payOrdersDate[] = $payOrder->date;
            $payOrdersValue[] = $payOrder->num;
        }

        foreach ($freeOrders as $freeOrder) {
            $freeOrdersDate[] = $freeOrder->date;
            $freeOrdersValue[] = $freeOrder->num;
        }


        return array($payOrdersDate, $payOrdersValue, $freeOrdersDate, $freeOrdersValue);
    }


    /**
     * 新增学员统计
     *
     * @return mixed
     */
    public function member($type)
    {
        $member = new PlanMember();

        switch ($type) {
            case 'month':
                $start = today()->startOfMonth();
                $end = today()->endOfMonth();
                $data = $member->whereBetween('created_at', [$start, $end])
                    ->groupBy('course_id')
                    ->with(['course' => function ($query) {
                        return $query->withTrashed()->select(['id', 'title', 'deleted_at']);
                    }])
                    ->orderBy('value', 'desc')
                    ->take(7)
                    ->get(['course_id', DB::raw('COUNT(*) as value')])
                    ->toArray();
                break;
            case 'week':
            default:
                $start = today()->subWeek();
                $end = today();

                $data = $member->whereBetween('created_at', [$start, $end])
                    ->with(['course' => function ($query) {
                        return $query->withTrashed()->select(['id', 'title', 'deleted_at']);
                    }])
                    ->groupBy('course_id')
                    ->orderBy('value', 'desc')
                    ->take(7)
                    ->get(['course_id', DB::raw('COUNT(*) as value')])
                    ->toArray();
                break;
        }

        return $data;
    }


    // 填充不完全的日期数据
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
