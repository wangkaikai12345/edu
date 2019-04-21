<?php

namespace App\Http\Controllers\Front\Manage;

use App\Enums\HomeworkPostStatus;
use App\Models\HomeworkGrade;
use App\Models\HomeworkPost;
use App\Models\Task;
use App\Models\ClassroomTeacher;
use App\Models\PlanTeacher;
use App\Models\TaskResult;
use App\Models\User;
use App\Notifications\HomeworkResultNotification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeworkPostController extends Controller
{
    /**
     * 批改作业列表
     */
    public function index(Request $request)
    {
        $username = $request->username;
        $status = $request->status;

        // 查询用户所有能管理的版本, 查询用户所有能管理的班级, 查询之后自动去重
        $uid = auth('web')->id();
        $planIds = PlanTeacher::where('user_id', $uid)->pluck('plan_id');

        $classroomIds = ClassroomTeacher::where('user_id', $uid)->pluck('classroom_id');

        $homeworkPosts = HomeworkPost::where('locked', 'open')
            ->where(function ($query) use ($planIds, $classroomIds) {
                return $query->whereIn('plan_id', $planIds)
                    ->orWhereIn('classroom_id', $classroomIds);
            })
            ->when($username, function ($query) use ($username) {
                $uids = User::where('username', 'like', "%{$username}%")->pluck('id');
                return $query->whereIn('user_id', $uids);
            })
            ->when($status, function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->with(['user', 'teacher', 'course', 'plan'])
            ->orderByDesc('id')
            ->paginate(config('theme.teacher_num'));
        return view('teacher.homework.rating', compact('homeworkPosts'));
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * 作业提交详情
     */
    public function show(HomeworkPost $homeworkPost)
    {
        $homework = $homeworkPost->homework;

        // 查询评分标准信息
        $grades = HomeworkGrade::whereIn('id', collect($homework->grades)->keys())->get()->keyBy('id');

        $retGrades = [];
        foreach ($homework->grades as $k => $g) {
            $retGrades[] = [
                'title' => $grades[$k]['title'],
                'score' => $g,
                'id' => $k
            ];
        }

        $grades_contents = [];
        foreach ($grades as $k => $v) {
            $grades_contents[$k] = [
                $v->comment_bad,
                $v->comment_middle,
                $v->comment_good,
            ];
        }

        return view('teacher.homework.info', compact('homeworkPost', 'homework', 'retGrades', 'grades_contents'));
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
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * 批改作业的模态框 或者 批改作业
     */
    public function read(Request $request, HomeworkPost $homeworkPost)
    {
        $homeworkPost->correct_media = $request->correct_media;
        $homeworkPost->grades = $request->grades;
        $homeworkPost->teacher_review = $request->teacher_review;
        $homeworkPost->status = HomeworkPostStatus::READED;
        $homeworkPost->result = $request->result;
        $homeworkPost->teacher_id = auth('web')->id();
        $homeworkPost->save();

        // 更新任务的学习结果
        $result = TaskResult::where([
            'task_id' => $homeworkPost->task_id,
            'user_id' => $homeworkPost->user_id,
        ])->first();

        if ($result) {

            $result->status = $homeworkPost->result >= 60 ? 'finish' : 'start';
            $result->save();
        }

        $chapter = Task::find($homeworkPost->task_id)->chapter;

        // 发送批改通知
        $homeworkPost->user->notify(new HomeworkResultNotification($chapter, $homeworkPost->classroom_id));

        return back()->withSuccess('作业批改成功');
    }
}
