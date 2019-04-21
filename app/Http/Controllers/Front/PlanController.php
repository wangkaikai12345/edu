<?php

namespace App\Http\Controllers\Front;

use App\Enums\OrderStatus;
use App\Enums\Payment;
use App\Enums\ProductType;
use App\Enums\SettingType;
use App\Http\Requests\Front\ReplyRequest;
use App\Http\Requests\Front\TopicRequest;
use App\Models\Chapter;
use App\Models\Order;
use App\Models\PlanMember;
use App\Models\Reply;
use App\Models\Topic;
use App\Traits\JoinTrait;
use Facades\App\Models\Setting;
use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\Front\ReviewRequest;
use App\Models\Course;
use App\Models\Plan;
use App\Models\PlanTeacher;
use App\Models\Review;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PlanController extends Controller
{
    use JoinTrait;

    /**
     * 版本的详情页
     *
     * @param Course $course
     * @param $plan
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author 王凯
     */
    public function show(Course $course, Plan $plan)
    {
//        if ($plan->status !== Status::PUBLISHED) abort(404);

        // 章节目录
        $chapters = $plan->chapterChildren();

        // 笔记信息
        $notes = $plan->notes()->with('user')->latest()->take(config('theme.plan_detail'))->get();

        // 评价信息
        $reviews = $plan->reviews()->with('user')->latest()->take(config('theme.plan_detail'))->get();

        // 成员信息
        $members = $plan->members()->with('user')->sorted()->take(config('theme.plan_member'))->get();

        // 教师信息
        $teachers = $plan->teachers()->with('user')->latest()->get();

        // 组装学习信息
        $buy = auth('web')->user() ? $plan->isMember(auth('web')->id()) : false;

        // 公告信息
        $notices = $plan->notices()->onShow()->get();

        $plan->course()->increment('hit_count');

        return frontend_view('course.article', compact('course', 'plan', 'chapters', 'notes', 'reviews', 'members', 'teachers', 'buy', 'notices'));
    }

    /**
     * 版本购买
     *
     * @param Course $course
     * @param Plan $plan
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author 王凯
     */
    public function shopping(Course $course, Plan $plan)
    {
        // 加入信息
        $member = $plan->members->where('user_id', auth('web')->id())->first();

        // 是否是版本的教师
        $control = $plan->isControl();

        if ($member || $control) {

            return redirect()->route('plans.intro', [$course, $plan]);
        }

        // 查询是否免费，直接加入
        if ($plan->is_free && !$plan->coin_price && !$plan->price) {
            if ($this->freeOrInside('plan', $plan->id, auth('web')->id(), 'free')) {
                return redirect()->route('plans.intro', [$course, $plan])->with('success', '加入版本成功');
            } else {
                return back()->withErrors('课程异常，暂时不能加入');
            }
        }

        // 查询购买版本是否已经创建订单
        $order = $plan->orders()->where(['user_id' => auth('web')->id(), 'status' => 'created'])->first();

        $alipay = '';
        $wechat = '';

        if ($order) {
            // 获取支付配置
            $alipay = Setting::namespace(SettingType::ALI_PAY)['on'];
            $wechat = Setting::namespace(SettingType::WECHAT_PAY)['on'];
        }

        return frontend_view('order.payment', compact('course', 'plan', 'order', 'alipay', 'wechat'));
    }

    /**
     * 版本介绍
     *
     * @param Course $course
     * @param Plan $plan
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author 王凯
     */
    public function intro(Course $course, Plan $plan)
    {
        $common = $this->common($course, $plan);
        return frontend_view('plan.intro', $common);
    }

    /**
     * 版本目录
     *
     * @param Course $course
     * @param Plan $plan
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author 王凯
     */
    public function chapter(Course $course, Plan $plan)
    {
        // 获取公共信息
        $common = $this->common($course, $plan);

        // 添加目录信息
        $common['chapters'] = $plan->chapterChildren();

        return frontend_view('plan.chapter', $common);
    }

    /**
     * 版本笔记
     *
     * @param Course $course
     * @param Plan $plan
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author 王凯
     */
    public function note(Course $course, Plan $plan)
    {
        $common = $this->common($course, $plan);

        $notes = $plan->notes()->with('user')->sorted()->paginate(config('theme.plan_detail'));

        $common = array_add($common, 'notes', $notes);

        return frontend_view('plan.note', $common);
    }

    /**
     * 版本评价
     *
     * @param Course $course
     * @param Plan $plan
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author 王凯
     */
    public function review(Course $course, Plan $plan)
    {
        $common = $this->common($course, $plan);

        $reviews = $plan->reviews()->with('user')->sorted()->paginate(config('theme.plan_detail'));

        $common = array_add($common, 'reviews', $reviews);

        // 查询当前用户的评价
        $current = $plan->reviews()->where('user_id', auth('web')->id())->first();

        $common = array_add($common, 'current', $current);

        return frontend_view('plan.review', $common);
    }

    /**
     * 版本评价创建
     *
     * @param Course $course
     * @param Plan $plan
     * @param ReviewRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @author 王凯
     */
    public function storeReview(Course $course, Plan $plan, ReviewRequest $request)
    {
        // 验证是否可评价
        if (!$plan->isMember()) return back()->with('danger', '您还没有加入版本');

        // 存在即更新，否则创建评价
        $review = Review::updateOrCreate(
            [
                'user_id' => auth('web')->id(),
                'course_id' => $course->id,
                'plan_id' => $plan->id,
            ],
            [
                'rating' => $request->score,
                'content' => request('content'),
            ]
        );

        if (!$review) return back()->with('danger', '服务器错误');

        return back()->with('success', '评价成功');
    }

    /**
     * 创建话题
     *
     * @param Course $course
     * @param Plan $plan
     * @param TopicRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @author 王凯
     */
    public function storeTopic(Course $course, Plan $plan, TopicRequest $request)
    {
        // 验证是否可评价
        if (!$plan->isMember()) return back()->with('danger', '您还没有加入版本');

        $topic = new Topic($request->all());
        $topic->user_id = auth('web')->id();
        $topic->course_id = $course->id;
        $topic->plan_id = $plan->id;
        $topic->hit_count = 5;
        $topic->save();

        return back()->with('success', '创建话题成功');
    }

    /**
     * 话题下创建回复
     *
     * @param Plan $plan
     * @param Topic $topic
     * @param ReplyRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @author 王凯
     */
    public function storeReply(Plan $plan, Topic $topic, ReplyRequest $request)
    {
        $reply = new Reply($request->all());
        $reply->course_id = $topic->course_id;
        $reply->plan_id = $topic->plan_id;
        $reply->topic_id = $topic->id;
        $reply->user_id = auth('web')->id();
        $reply->save();

        $topic->latest_replier_id = auth('web')->id();
        $topic->latest_replied_at = Carbon::now();
        $topic->save();

        $reply['user'] = $reply->user;

        return ajax('200', '回复成功', $reply);
    }

    /**
     * 话题下的回复
     *
     * @param Plan $plan
     * @param Topic $topic
     * @return \Illuminate\Http\JsonResponse
     * @author 王凯
     */
    public function reply(Plan $plan, Topic $topic)
    {
        $data = $topic->replies()->with('user')->latest()->get();

        return ajax('200', '', $data);
    }

    /**
     * 版本话题
     *
     * @param Course $course
     * @param Plan $plan
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author 王凯
     */
    public function topic(Course $course, Plan $plan)
    {
        $common = $this->common($course, $plan);

        $topics = $plan->topics()->where('type', 'discussion')->with('user')->latest()->paginate(config('theme.plan_detail'));

        $common = array_add($common, 'topics', $topics);

        return frontend_view('plan.topic', $common);
    }

    /**
     * 版本问答
     *
     * @param Course $course
     * @param Plan $plan
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author 王凯
     */
    public function question(Course $course, Plan $plan)
    {
        $common = $this->common($course, $plan);

        $topics = $plan->topics()->where('type', 'question')->with('user')->latest()->paginate(config('theme.plan_detail'));

        $common = array_add($common, 'topics', $topics);

        return frontend_view('plan.question', $common);
    }

    /**
     *  版本详情页面展示的详情信息
     *
     * @param $course
     * @param $plan
     * @return array
     * @author 王凯
     */
    protected function common($course, $plan)
    {
        // 版本信息
//        if ($plan->status !== Status::PUBLISHED) abort(404);

        if (!$plan->isValid()) abort(404);

        // 加入信息
        $plan_member = $plan->members->where('user_id', auth('web')->id())->first();

        // 是否是版本的教师
        $control = $plan->isControl();

        if (!$plan_member && !$control) return redirect()->to(route('courses.show', $course));

        // 任务信息
        $task = $plan->getLearningTask();

        // 成员信息
        $members = $plan->members()->with('user')->sorted()->take(config('theme.plan_member'))->get();

        // 教师信息
        $teachers = $plan->teachers()->with('user')->latest()->get();

        // 公告信息
        $notices = $plan->notices()->onShow()->get();

        return compact('course', 'plan', 'task', 'plan_member', 'members', 'teachers', 'control', 'notices');

    }
}
