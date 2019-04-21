<?php

namespace App\Http\Controllers\Front\Manage;

use App\Http\Controllers\Front\ConversationController;
use App\Http\Requests\Front\PlanMemberRequest;
use App\Models\Course;
use App\Models\Plan;
use App\Models\PlanMember;
use App\Models\User;
use function foo\func;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PlanMemberController extends Controller
{
    /**
     * 学员列表
     */
    public function index(Request $request, Course $course, Plan $plan)
    {
        $keyword = $request->keyword;
        $members = $plan->members()->with(['user' => function ($query) {
            return $query->select('id', 'username', 'avatar');
        }])->when($keyword ,function ($query) use ($keyword) {
            $uids = User::where('username', 'like', "%{$keyword}%")
                ->orWhere('email', $keyword)
                ->orWhere('phone', $keyword)
                ->pluck('id');
            return $query->whereIn('user_id', $uids);
        })->paginate(config('theme.teacher_num'));
        return view('teacher.student.index', compact('course', 'plan', 'members'));
    }

    /**
     *
     */
    public function create()
    {
        //
    }

    /**
     * 添加学员
     */
    public function store(Course $course, Plan $plan, PlanMemberRequest $request, PlanMember $planMember)
    {
        !$plan->isControl() && abort(404);

        $keyword = $request->keyword;
        $user = User::where('username', $keyword)
            ->orWhere('email', $keyword)
            ->orWhere('phone', $keyword)
            ->first();

        if (empty($user)) return ajax('204', '没有这个用户!');

        $res = $planMember->freeOrInside('plan', $plan->id, $user->id, 'inside');
        if ($res) {
            return ajax('200', '添加学员成功!');
        } else {
            return ajax('400', '添加学员失败!');
        }
    }

    /**
     * 学员详情
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除学员
     */
    public function destroy(PlanMember $member)
    {
        if ($member->delete()) {
            return ajax('200', '删除成员成功!');
        } else {
            return ajax('400', '删除成员失败!');
        }
    }

    /**
     * 为学员设置备注
     */
    public function remark(Request $request, PlanMember $member)
    {
        if ($request->isMethod('post')) {
            $member->remark = $request->remark;
            $member->save();
            return ajax('200', '修改学员备注成功!');
        }
        return view('teacher.student.modal.remark-modal', compact('member'));
    }

    /**
     * 为学员发送私信
     */
    public function message(Request $request, PlanMember $member, ConversationController $msg)
    {
        if ($request->isMethod('post')) {
            $msg->store($request);

            return ajax('200', '给学员发送私信成功!');
        }
        return view('teacher.student.modal.message-modal', compact('member'));
    }

    /**
     * 查看用户信息
     */
    public function userinfo(PlanMember $member)
    {
        $user = $member->user;
        $profile = $user->profile;
        return view('teacher.student.modal.userinfo-modal', compact('member', 'user', 'profile'));
    }
}
