<?php

namespace App\Http\Controllers\Front\Manage;

use App\Enums\Status;
use App\Enums\UserType;
use App\Models\Category;
use App\Models\CategoryGroup;
use App\Models\Classroom;
use App\Models\ClassroomCourse;
use App\Models\ClassroomTeacher;
use App\Models\Tag;
use App\Models\TagGroup;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClassroomController extends Controller
{
    /**
     * 班级管理列表页
     */
    public function index(Request $request, Classroom $classroom)
    {
        $title = $request->title;
        $username = $request->username;
        $classrooms = $classroom->where(function ($query) use ($username) {
                $uid = auth('web')->id();
                if (!empty($username)) {
                    $user = User::where('username', 'like', "%{$username}%")->first();
                    $uid = !empty($user) ? $user->id : null;
                }
                return $query ->where('user_id', $uid);
            })
            ->when($title, function ($query) use ($title) {
                return $query->where('title', 'like', '%' . $title . '%');
            })
            ->paginate(config('theme.teacher_num'));
        return view('teacher.classroom.create.index', compact('classrooms'));
    }

    /**
     *
     */
    public function create()
    {
        //
    }

    /**
     * 创建班级
     */
    public function store(Request $request, Classroom $classroom)
    {
        if (!auth('web')->user()->isAdmin()) {
            return ajax('400', '权限不够!');
        }
        $classroom->title = $request->title;
        $classroom->user_id = auth('web')->id();
        $classroom->is_show = 1;
        $classroom->is_buy = 1;
        $classroom->services = [];
        $classroom->learn_mode = 'pass';
        $classroom->expiry_mode = 'forever';
        $classroom->save();
        return ajax('200', '创建班级成功!');
    }

    /**
     * 班级管理首页
     */
    public function show(Classroom $classroom)
    {
        // 计算阶段数量
        $plans = $classroom->plans;
        $plansCount = $plans->count();
        $chaptersCount = 0;
        foreach ($plans as $plan) {
            $chaptersCount += $plan->chapters->where('parent_id', 0)->count();
        }
        $memberCount = $classroom->members->count();
        return view('teacher.classroom.management.index', compact('classroom', 'plansCount', 'chaptersCount', 'memberCount'));
    }

    /**
     *
     */
    public function edit($id)
    {
        //
    }

    /**
     * 基本信息页面
     */
    public function base(Request $request, Classroom $classroom)
    {
        // 查询课程分类
        $categoryGroup = CategoryGroup::where('name', 'course')->first();
        $category = $categoryGroup ? Category::where('category_group_id', $categoryGroup->id)->get() : [];

        $labels = Tag::select('id', 'name as text')
            ->where(function ($q){
                return $q->where('tag_group_id', TagGroup::where('name', 'course')->first()->id);
            })->get()->toArray();

        return view('teacher.classroom.basic.index', compact('classroom', 'category', 'labels'));
    }

    /**
     * 更新班级信息
     */
    public function update(Request $request, Classroom $classroom)
    {
        $classroom->fill(array_only($request->all(), [
            'title',
            'description',
            'category_id',
            'cover',
            'preview',
            'learn_mode',
            'is_buy',
            'is_show',
            'expiry_mode',
            'expiry_started_at',
            'expiry_ended_at',
            'expiry_days',
        ]));
        $classroom->save();

        // 处理标签
        $classroom->tags()->sync($request->input('tags', []));

        return back()->withSuccess('更新成功!');
    }

    /**
     *
     */
    public function destroy($id)
    {
        //
    }

    /**
     * 发布班级
     */
    public function publish(Classroom $classroom)
    {
        if ($classroom->status != Status::PUBLISHED) {
            $classroom->status = Status::PUBLISHED;
            $classroom->save();
            return back()->withSuccess('班级发布成功!');

        } else {
            $classroom->status = Status::DRAFT;
            $classroom->save();
            return back()->withSuccess('班级取消发布成功!');
        }
    }

    /**
     * 价格设置
     */
    public function price(Request $request, Classroom $classroom)
    {
        if ($request->isMethod('patch')) {
            if ($request->price < 0) {
                return back()->withErrors('请输入正确的价格!');
            }

            $classroom->price = $request->price * 100;
            $classroom->save();

            return back()->withSuccess('价格设置成功!');
        }

        // 查询所有班级所有的课程, 并计算所有课程的价格
        $plans = ClassroomCourse::where('classroom_id', $classroom->id)->get();
        $plansCount = $plans->count();
        $plansPrice = $plans->pluck('price')->sum();

        return view('teacher.classroom.price.index', compact('classroom', 'plansCount', 'plansPrice'));
    }

    /**
     * 服务设置
     */
    public function service(Request $request, Classroom $classroom)
    {
        if ($request->isMethod('post')) {
            $classroom->services = empty($request->services) ? [] : $request->services;
            $classroom->save();
            return back()->withSuccess('服务设置成功!');
        }
        $services = config('theme.services');
        return view('teacher.classroom.service.index', compact('classroom', 'services'));
    }

    /**
     * 教师设置-页面
     */
    public function teacher(Request $request, Classroom $classroom)
    {
        // 查询所有教师角色的用户
        $teachers = User::role(UserType::TEACHER)->get();

        return view('teacher.classroom.teacher.index', compact('classroom', 'teachers'));
    }

    /**
     * 教师设置-保存
     */
    public function storeTeachers(Request $request, Classroom $classroom)
    {
        // 判断是否拥有教师角色
        $user = User::role(UserType::TEACHER)->find($request->user_id);
        if (empty($user)) {
            return ajax('204', '用户不存在, 或者不是教师!');
        }

        // 查询是否该课程已经加过这个老师
        $check = ClassroomTeacher::where('user_id', $classroom->id)->where('user_id', $user->id)->count();
        if ($check > 0) return ajax('204', '不能重复添加!');

//        $maxSeq = $plan->teachers()->max('seq') ?? 0;

        $teacher = new ClassroomTeacher();
        $teacher->user_id = $user->id;
        $teacher->classroom_id = $classroom->id;
        $teacher->type = $request->type;
        $teacher->save();

        $avatar = render_cover($user->avatar, 'avatar') ?? '/imgs/avatar.png';
        $url = route('manage.plans.teachers.delete', [$teacher->id]);
        return ajax('200', '设置教师成功!', compact('avatar', 'url'));
    }

    /**
     * 删除教师
     */
    public function deleteTeachers(Request $request, Classroom $classroom, User $user)
    {
        $teacher = ClassroomTeacher::where('classroom_id', $classroom->id)
            ->where('user_id', $user->id)
            ->where('type', $request->type)
            ->first();

        if ($teacher->delete()) {
            return ajax('200', '删除教师成功!');
        } else{
            return ajax('400', '删除教师失败!');
        }
    }
}
