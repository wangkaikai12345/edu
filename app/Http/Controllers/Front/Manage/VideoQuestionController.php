<?php

namespace App\Http\Controllers\Front\Manage;

use App\Models\ClassroomCourse;
use App\Models\ClassroomTeacher;
use App\Models\Course;
use App\Models\Paper;
use App\Models\PaperQuestion;
use App\Models\PaperResult;
use App\Models\Plan;
use App\Models\PlanTeacher;
use App\Models\Task;
use App\Models\VideoQuestion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class VideoQuestionController extends Controller
{
    /**
     * 视频答题统计首页
     */
    public function index(Course $course, Plan $plan)
    {
        $uid = auth('web')->id();
        // 查询用户所有能管理的版本
        $planIds_course = PlanTeacher::where('user_id', $uid)->pluck('plan_id')->toArray();

        $classroomIds = ClassroomTeacher::where('user_id', $uid)->pluck('classroom_id');

        $planIds_class = ClassroomCourse::whereIn('classroom_id', $classroomIds)->pluck('plan_id')->toArray();

        $plan_ids = array_merge($planIds_course, $planIds_class);

        $paperResults = PaperResult::whereIn('plan_id', $plan_ids)
            ->where('mark_type', 'auto')
            ->get();

        $paperIds = $paperResults->unique('paper_id')->pluck('paper_id');

        $video_papers = VideoQuestion::whereIn('paper_id', $paperIds)->get()->keyBy('paper_id');

        // 查询所有试卷
        $papers = Paper::whereIn('id', $paperIds)->paginate(config('theme.teacher_num'));

        // 循环所有试卷, 计算相关统计
        foreach ($papers as $paper) {
            $prs = $paperResults->where('paper_id', $paper->id);
            $allCount= $prs->count();
            $paper->numbers = $allCount;
            $paper->persons = $allCount;
            $paper->trueCount = $prs->where('score', '>', 60)->count();
            // 查询时间点
            $paper->video_time = $video_papers[$paper->id]->video_time;
        }

        return view('teacher.plan.question_count', compact('course', 'plan', 'papers'));
    }

    /**
     * 视频答题创建页面
     */
    public function create(Course $course, Task $task)
    {
        $plan = $task->plan;

        // 查询任务下的所有评论
        $notes = $task->notes()->latest()->paginate(5);

        return view('teacher.plan.question_manage', compact('course', 'plan', 'task', 'notes'));
    }

    /**
     * 保存视频答题
     */
    public function store(
        Request $request,
        Course $course,
        Task $task, Paper $paper,
        PaperQuestion $paperQuestion,
        VideoQuestion $videoQuestion)
    {
        try {
            DB::beginTransaction();

            // 1. 生成试卷-为视频类型(试卷分测试类型和视频类型)
            $paper->title = $request->title;
            $paper->expect_time = 300;
            $paper->total_score = 100;
            $paper->pass_score = 60;
            $paper->type = 'video';
            $paper->questions_count = count($request->datas);
            $paper->user_id = auth('web')->id() ?? 1;
            $paper->save();

            // 生成试卷和题目的关联表
            $insertData = [];
            foreach ($request->datas as $qid) {
                $insertData[] = [
                    'paper_id' => $paper->id,
                    'question_id' => $qid['id'],
                    'score' => $qid['score'],
                ];
            }
            $paperQuestion->newQuery()->insert($insertData);

            // 2. 生成视频和视频的关联
            $videoQuestion->paper_id = $paper->id;
            $videoQuestion->video_id = $task->target_id;
            $videoQuestion->video_time = $request->time;
            $videoQuestion->save();

            DB::commit();
        } catch (\Exception $e) {
            \Log::info($e);
            DB::rollback();
            return ajax('400', '创建弹题失败!');
        }

        return ajax('200', '创建弹题成功!');
    }

    /**
     * 一个试卷的详情
     */
    public function show(Paper $paper)
    {
        return view('teacher.plan.modal.view-details', compact('paper'));
    }

    /**
     *
     */
    public function edit($id)
    {
        //
    }

    /**
     *
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     *
     */
    public function destroy(VideoQuestion $videoQuestion)
    {
        if ($videoQuestion->delete()) {
            return ajax('200', '删除成功!');
        } else {
            return ajax('400', '删除失败!');
        }
    }
}
