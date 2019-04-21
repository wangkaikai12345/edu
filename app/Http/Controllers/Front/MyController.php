<?php

namespace App\Http\Controllers\Front;

use App\Enums\FavoriteType;
use App\Enums\SettingType;
use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\Front\PasswordByOldPasswordRequest;
use App\Models\Classroom;
use App\Models\ClassroomMember;
use App\Models\Favorite;
use App\Models\HomeworkPost;
use App\Models\Order;
use App\Models\PaperResult;
use App\Models\PlanMember;
use App\Models\PlanTeacher;
use App\Models\Recharging;
use App\Models\User;
use Illuminate\Http\Request;
use Facades\App\Models\Setting;
use Hash;
use Cache;

class MyController extends Controller
{
    /**
     * 账户中心
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author 王凯
     */
    public function safe()
    {
        return frontend_view('personal.security');
    }

    /**
     * 虚拟币
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author 王凯
     */
    public function coin(Request $request)
    {
        // 查询用户的订单
        $orders = auth('web')->user()
            ->orders()
            ->where('status', 'success')
            ->where(function ($query) {
                return $query->where('product_type', 'recharging')
                    ->orWhere('currency', 'coin');
            })
            ->latest()
            ->paginate(config('theme.plan_detail'));

        return frontend_view('personal.virtualCurrency', compact('orders'));
    }

    /**
     * 购买虚拟币订单页
     *
     * @param $coin
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author 王凯
     */
    public function coinShop(Recharging $coin)
    {

        // 查询虚拟币是否已经创建订单
        $order = $coin->orders()->where(['user_id' => auth('web')->id(), 'status' => 'created'])->first();

        $alipay = [];
        $wechat = [];

        if ($order) {
            // 获取支付配置
            $alipay = Setting::namespace(SettingType::ALI_PAY)['on'];
            $wechat = Setting::namespace(SettingType::WECHAT_PAY)['on'];
        }

        return frontend_view('coin.shopping', compact('order', 'alipay', 'wechat', 'coin'));
    }

    /**
     * 我的课程
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author 王凯
     */
    public function courses(PlanMember $planMember, Request $request)
    {
        if ($request->favourite) {
            // 默认展示自己收藏
            $request->offsetSet('user_id', auth('web')->id());
            // 默认展示课程收藏
            $request->offsetSet('model_type', FavoriteType::COURSE);

            $courses = Favorite::filtered()->with('model')->sorted()->paginate(config('theme.my_course_num'));

        } else {
            $request->is_finished ?? $request->offsetSet('is_finished', 0);

            // 默认查询学习中
            $courses = $planMember->filtered()
                ->sorted()
                ->where('user_id', auth('web')->id())
                ->with(['course', 'plan'])
                ->paginate(config('theme.my_course_num'));
        }

        return frontend_view('learn.course', compact('courses'));
    }

    /**
     * 我的班级
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author 王凯
     */
    public function classrooms()
    {
        // 获取当前登录用户所有在学班级
        $members = ClassroomMember::where('user_id', \Auth::id())
            ->latest()
            ->with('classroom')
            ->paginate(config('theme.my_course_num'));

        return frontend_view('learn.classroom', compact('members'));
    }

    /**
     * 我的笔记
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author 王凯
     */
    public function notes()
    {
        $notes = auth('web')->user()
            ->notes()
            ->filtered()
            ->sorted()
            ->with(['course', 'plan', 'task'])
            ->paginate(config('theme.my_course_num'));

        return frontend_view('learn.note', compact('notes'));
    }

    /**
     * 我的话题
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author 王凯
     */
    public function topics()
    {
        $topics = auth('web')->user()
            ->topics()
            ->where('type', 'discussion')
            ->sorted()
            ->with(['course', 'plan'])
            ->paginate(config('theme.my_course_num'));

        return frontend_view('learn.topic', compact('topics'));
    }

    /**
     * 我的问答
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author 王凯
     */
    public function questions()
    {
        $topics = auth('web')->user()
            ->topics()
            ->where('type', 'question')
            ->sorted()
            ->with(['course', 'plan', 'task'])
            ->paginate(config('theme.my_course_num'));

        return frontend_view('learn.question', compact('topics'));
    }

    /**
     * 我的作业
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author 王凯
     */
    public function jobs(Request $request)
    {
        // 查询为批阅还是已批阅
        $keyword = $request->keyword;

        // 用户作业列表
        $homeworks = auth('web')->user()
            ->homeworks()
            ->where('locked', 'open')
            ->when($keyword, function ($query) use ($keyword) {
                if ($keyword == 'reading') {
                    $query->where('status', 'reading');
                } else {
                    $query->where('status', 'readed');
                }
            })->sorted()
            ->with(['course', 'plan'])
            ->paginate(config('theme.my_course_num'));

        return frontend_view('learn.homework', compact('homeworks'));
    }

    /**
     * 作业详情模态框信息
     *
     * @param HomeworkPost $homeworkPost
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function homeworkInfo(HomeworkPost $homeworkPost)
    {
        return frontend_view('learn.homework-modal', compact('homeworkPost'));
    }

    /**
     * 我的考试
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author 王凯
     */
    public function exams()
    {
        // 获取当前用户的所有考试记录
        $paper_results = PaperResult::where('user_id', \Auth::id())
            ->paginate(10);
        return frontend_view('learn.exam', compact('paper_results'));
    }

    /**
     * 一个考试的详情
     *
     * @param PaperResult $result
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function examsDetails(PaperResult $result)
    {
        return frontend_view('learn.exam-details', compact('result'));
    }


    /**
     * 绑定成功
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author 王凯
     */
    public function bindPhone(Request $request)
    {
        $me = auth('web')->user();

        $this->validate($request, [
            'sms_key' => 'required|string',
            'sms_code' => 'required|string',
            'password' => 'required|min:6'
        ]);

        // 获取验证码 KEY
        if (!($cacheData = Cache::get($request->sms_key))) return ajax('400', __('Verification code has been expired.'));

        // 验证码不匹配
        if ($cacheData['code'] != $request->sms_code) return ajax('400', __('Verification code is error.'));

        // 密码验证
        if (!Hash::check($request->input('password'), $me->getAuthPassword())) return ajax('400', __('Password error.'));

        $me->is_phone_verified = true;
        $me->phone = $cacheData['phone'];
        $me->save();

        Cache::forget($request->sms_key);

        return ajax('200', '手机绑定成功');
    }

    /**
     * 通过旧密码重置密码
     *
     * @param PasswordByOldPasswordRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @author 王凯
     */
    public function password(PasswordByOldPasswordRequest $request)
    {
        $me = auth('web')->user();

        if (!Hash::check($request->old_password, $me->getAuthPassword())) {
            return ajax('400', __('Password error.'));
        }

        $me->password = bcrypt($request->password);
        $me->save();

        return ajax('200', '密码更新成功');
    }

    /**
     * 邮箱绑定
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @author 王凯
     */
    public function bindEmail(Request $request)
    {
        $row = PasswordReset::where('token', $request->code)->first();

        $emailSetting = Setting::namespace(SettingType::EMAIL);

        $expiredMinutes = data_get($emailSetting, 'expires', 24 * 60);

        if (!$row || $row->created_at->addMinutes($expiredMinutes)->gt(now())) {
            $verified = 0;
        } else {
            $user = User::where('email', $row->email)->first();
            $user->is_email_verified = true;
            $user->save();
            $verified = 1;
        }

//        $queryString = http_build_query(['is_email_verified' => $verified]);

        return redirect()->to(route('users.safe'));
    }

}