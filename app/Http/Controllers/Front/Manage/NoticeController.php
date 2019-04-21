<?php

namespace App\Http\Controllers\Front\Manage;

use App\Enums\NoticeType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\PlanNoticeRequest;
use App\Models\Course;
use App\Models\Notice;
use App\Models\Plan;

class NoticeController extends Controller
{
    /**
     * 版本公告的列表
     */
    public function index(Course $course, Plan $plan)
    {
        $notices = $plan->notices;
        return view('teacher.plan.notice-list', compact('course', 'plan', 'notices'));
    }

    /**
     * 公告添加页面
     */
    public function create(Plan $plan)
    {
        return view('teacher.plan.modal.notice-add-modal', compact('plan'));
    }

    /**
     * 为版本添加公告
     */
    public function store(Plan $plan, PlanNoticeRequest $request)
    {
        $notice = new Notice($request->all());
        $notice->type = NoticeType::PLAN;
        $notice->user_id = auth('web')->id();
        $notice->plan_id = $plan->id;
        $notice->save();
        return ajax('200', '公告添加成功!');
    }

    /**
     * 编辑公告页面
     */
    public function edit(Notice $notice)
    {
        return view('teacher.plan.modal.notice-edit-modal', compact('notice'));
    }

    /**
     * 更新版本
     */
    public function update(Notice $notice, PlanNoticeRequest $request)
    {
        $notice->fill($request->all());
        $notice->save();
        return ajax('200', '公告更新成功!');
    }

    /**
     * 删除公告
     */
    public function destroy(Notice $notice)
    {
        $notice->delete();
        return ajax('200', '公告删除成功!');
    }
}
