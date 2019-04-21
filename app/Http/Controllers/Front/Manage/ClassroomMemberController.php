<?php

namespace App\Http\Controllers\Front\Manage;

use App\Http\Controllers\Front\ConversationController;
use App\Enums\StudentStatus;
use App\Models\Classroom;
use App\Models\ClassroomMember;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClassroomMemberController extends Controller
{
    /**
     * 班级成员列表 config('theme.teacher_num')
     */
    public function index(Request $request, Classroom $classroom)
    {
        $title = $request->title;
        $members = ClassroomMember::where('classroom_id', $classroom->id)
            ->when($title, function ($query) use ($title) {
                $uids = User::where('username', 'like', "%{$title}%")
                    ->orWhere('email', $title)
                    ->orWhere('phone', $title)
                    ->pluck('id');
                return $query->whereIn('user_id', $uids);
            })
            ->paginate(config('theme.teacher_num'));
        return view('teacher.classroom.student.index', compact(
            'classroom',
            'members'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Classroom $classroom, ClassroomMember $classroomMember)
    {
        // 查询用户是否存在, 如果不存在, 直接返回
        $user = User::where('username', $request->title)
            ->orWhere('email', $request->title)
            ->orWhere('phone', $request->title)
            ->first();
        if (empty($user)) return back()->withErrors('用户不存在, 无法添加用户到班级!');

        $classroomMember->user_id = $user->id;
        $classroomMember->classroom_id = $classroom->id;
        $classroomMember->remark = $request->remark;
        $classroomMember->type = $request->type;
        $classroomMember->status = StudentStatus::BEGINNING;
        $classroomMember->save();

        // TODO 生成相关的订单

        return back()->withSuccess('添加用户到班级成功!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除学员
     */
    public function destroy(ClassroomMember $member)
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
    public function remark(Request $request, ClassroomMember $member)
    {
        if ($request->isMethod('post')) {
            $member->remark = $request->remark;
            $member->save();
            return ajax('200', '修改学员备注成功!');
        }
        return view('teacher.classroom.modal.remark-modal', compact('member'));
    }

    /**
     * 为学员发送私信
     */
    public function message(Request $request, ClassroomMember $member, ConversationController $msg)
    {
        if ($request->isMethod('post')) {
            $msg->store($request);

            return ajax('200', '给学员发送私信成功!');
        }
        return view('teacher.classroom.modal.message-modal', compact('member'));
    }

    /**
     * 查看用户信息
     */
    public function userinfo(ClassroomMember $member)
    {
        $user = $member->user;
        $profile = $user->profile;
        return view('teacher.classroom.modal.userinfo-modal', compact('member', 'user', 'profile'));
    }
}
