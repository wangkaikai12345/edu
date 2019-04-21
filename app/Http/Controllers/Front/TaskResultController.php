<?php

namespace App\Http\Controllers\Front;

use App\Enums\TaskTargetType;
use App\Enums\TaskResultStatus;
use App\Models\ClassroomCourse;
use App\Models\ClassroomMember;
use App\Models\HomeworkPost;
use App\Models\Paper;
use App\Models\PaperResult;
use App\Models\PlanMember;
use App\Models\QuestionResult;
use App\Models\Task;
use App\Http\Controllers\Controller;
use App\Models\TaskResult;
use App\Models\VideoQuestion;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TaskResultController extends Controller
{
    /**
     * 更新学习轨迹
     *
     * 1.开篇
     * 2.测评
     * 3.任务
     * 4.作业
     * 5.扩展
     *
     * @param Task $task
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author 王凯
     */
    public function update(Task $task, Request $request)
    {
        $this->validate($request, ['time' => 'required|numeric']);

        $me = auth('web')->user();

        $classroomMember = '';

        if ($cid = $request->cid) {
            $classroomMember = ClassroomMember::where(['user_id' => $me->id, 'classroom_id' => $cid])->first();
        }

        // 若为学员则查询用户是否在该教学版本之中
        $planMember = PlanMember::where('user_id', $me->id)->where('plan_id', $task->plan_id)->first();

        if (!$planMember && !$classroomMember) {
            return ajax('400', __('Not a member of the course.'));
        }

        // 不同任务模式，执行不同的校验标准
        switch ($task->target_type) {
            case TaskTargetType::VIDEO:
            case TaskTargetType::AUDIO:
                return $this->video($task, $classroomMember);
                break;
            case TaskTargetType::PPT:
            case TaskTargetType::DOC:
            case TaskTargetType::TEXT:
            case TaskTargetType::ZIP:
            case TaskTargetType::HOMEWORK:
                return $this->ppt($task, $classroomMember);
                break;
            default;
        }
    }

    /**
     * 考试
     *
     * @param Task $task
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author 王凯
     */
    public function paper(Task $task, Request $request)
    {
        // 数据验证
        $this->validate($request, ['answer' => 'required|array', 'time' => 'required |numeric|min:0']);

        $answers = $request->answer;
        // 剩余时间
        $time = $request->time;

        // 考试记录专用
        if ($task->target_type != 'paper') {
            return ajax('400', '任务模式错误');
        }

        $me = auth('web')->user();

        $classroomMember = '';

        if ($cid = $request->cid) {
            $classroomMember = ClassroomMember::where(['user_id' => $me->id, 'classroom_id' => $cid])->first();
        }

        // 若为学员则查询用户是否在该教学版本之中
        $planMember = PlanMember::where('user_id', $me->id)->where('plan_id', $task->plan_id)->first();

        if (!$planMember && !$classroomMember) {
            return ajax('400', __('Not a member of the course.'));
        }

        // 获取试卷信息
        $paper = $task->target;
        if (!$paper) {
            return ajax('400', '试卷信息不存在');
        }

        // 获取试卷题目信息
        $questions = $task->target->questions;
        if (!$questions->count()) {
            return ajax('400', '试卷题目信息不存在');
        }

        \DB::transaction(function () use ($task, $paper, $questions, $answers, $time, $classroomMember) {

            // 创建考试记录信息
            $paperResult = new PaperResult();

            $paperResult->task_id = $task->id;
            $paperResult->plan_id = $task->plan_id;
            $paperResult->paper_title = $paper->title;
            $paperResult->paper_id = $paper->id;
            $paperResult->user_id = auth('web')->id();
            $paperResult->score = $paper->total_score;
            $paperResult->pass_score = $paper->pass_score;
            // 考试用时
            $paperResult->time = $time ? $time : $paper->expect_time;
            $paperResult->start_at = Carbon::now()->subSeconds($paperResult->time);
            $paperResult->end_at = now();
            $paperResult->is_mark = 0;

            $paperResult->save();

            // 答题分数
            $answer_score = 0;
            $right_count = 0;
            $false_count = 0;
            $finished_count = 0;

            // 判断是否有主观题
            $isObject = false;

            // 统计答题结果
            foreach ($questions as $question) {

                // 如果是主观题
                if ($question->type == 'answer') {
                    $isObject = true;
                }

                // 未答题
                if (!array_key_exists($question->id, $answers)) {

                    // 试卷错误次数统计
                    $false_count++;

                    // 创建题目答题记录
                    $questionResult = new QuestionResult();
                    $questionResult->task_id = $task->id;
                    $questionResult->paper_result_id = $paperResult->id;
                    $questionResult->paper_id = $paper->id;
                    $questionResult->question_id = $question->id;
                    $questionResult->user_id = auth('web')->id();
                    $questionResult->status = 'noanswer';
                    $questionResult->type = $question->type;
                    $questionResult->rate = $question->rate;
                    $questionResult->score = 0;
                    $questionResult->save();
                    continue;
                }

                // 答过的题
                foreach ($answers as $key => $answer) {

                    if ($key == $question->id) {

                        // 已答题数据统计
                        $finished_count++;

                        // 创建题目答题记录
                        $questionResult = new QuestionResult();

                        $questionResult->task_id = $task->id;
                        $questionResult->paper_result_id = $paperResult->id;
                        $questionResult->paper_id = $paper->id;
                        $questionResult->question_id = $question->id;
                        $questionResult->user_id = auth('web')->id();
                        // 主观题答案
                        $questionResult->objective_answer = json_encode($answer);
                        $questionResult->subjective_answer = $question->type == 'answer' ? $answer : '';
                        $questionResult->status = $question->type == 'answer' ? 'noread' : ($answer == $question->answers ? 'right' : 'error');
                        $questionResult->type = $question->type;
                        $questionResult->rate = $question->rate;
                        $questionResult->score = $question->type == 'answer' ? 0 : ($answer == $question->answers ? $question->pivot->score : 0);;

                        $questionResult->save();

                        // 正确错误答题统计，得分统计
                        if ($question->type != 'answer') {
                            if ($answer == $question->answers) {
                                $right_count++;
                                $answer_score += $question->pivot->score;
                            } else {
                                $false_count++;
                            }
                        }

                    }
                }
            }

            // 选择题自动判卷
            $paperResult->answer_score = $answer_score;
            $paperResult->right_count = $right_count;
            $paperResult->false_count = $false_count;
            $paperResult->finished_count = $finished_count;
            // 大于及格分数即为完成
            $paperResult->is_finished = $answer_score >= $paper->pass_score ? 1 : 0;

            $paperResult->is_mark = $isObject ? 0 : 1;

            // 更新试卷考试
            $paperResult->save();

            // 更新任务结果
            $this->test($task, $classroomMember);

        });

        $res = typeResult($task->chapter_id, $task->type, auth('web')->id());

        return ajax('200', '测试结束', ['status' => $res]);
    }

    /**
     * 提交作业
     *
     * @param Task $task
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author 王凯
     */
    public function homework(Task $task, Request $request)
    {
        // 数据验证
        $this->validate($request, [
            'title' => 'required|max:64',
        ]);
        $me = auth('web')->user();

        $classroomMember = '';

        if ($cid = $request->cid) {
            $classroomMember = ClassroomMember::where(['user_id' => $me->id, 'classroom_id' => $cid])->first();
        }

        // 若为学员则查询用户是否在该教学版本之中
        $planMember = PlanMember::where('user_id', $me->id)->where('plan_id', $task->plan_id)->first();

        if (!$planMember && !$classroomMember) {
            return ajax('400', __('Not a member of the course.'));
        }

        $homework = $task->target;

        if (!$homework) {
            return ajax('400', '作业不存在');
        }

        // 验证作业提交类型
        if (in_array('img', $homework->post_type)) {
            $this->validate($request, [
                'post_img' => 'required|array',
            ]);
        }

        if (in_array('zip', $homework->post_type)) {
            $this->validate($request, [
                'package' => 'required|string',
            ]);
        }

        if (in_array('code', $homework->post_type)) {
            $this->validate($request, [
                'code' => 'required|string',
            ]);
        }

        \DB::transaction(function () use ($request, $homework, $task, $planMember, $classroomMember) {


            // 查询是否已经提交
            $res = HomeworkPost::where([
                'classroom_id' => $classroomMember ? $classroomMember->classroom_id : 0,
                'task_id' => $task->id,
                'user_id' => auth('web')->id(),
            ])->first();

            if ($res) {
                $res->locked = 'locked';
                $res->save();
            }

            // 作业提交记录数据插入
            $homeworkPost = new HomeworkPost();

            $homeworkPost->title = $request->title;
            $homeworkPost->classroom_id = $classroomMember ? $classroomMember->classroom_id : 0;
            $homeworkPost->homework_id = $homework->id;
            $homeworkPost->course_id = $task->course_id;
            $homeworkPost->plan_id = $task->plan_id;
            $homeworkPost->task_id = $task->id;
            $homeworkPost->package = $request->package ? $request->package : '';
            $homeworkPost->code = $request->code ? $request->code : '';
            $homeworkPost->post_img = $request->post_img ? $request->post_img : '';
            $homeworkPost->user_id = auth('web')->id();
            $homeworkPost->student_review = $request->student_review ? $request->student_review : '';

            $homeworkPost->save();

            // 标记任务完成
            $this->home($task, $classroomMember);
        });


        $res = typeResult($task->chapter_id, $task->type, auth('web')->id());
        return ajax('200', '作业提交成功', ['status' => $res]);

    }

    /**
     * 音频、视频类任务结果校验
     *
     * @param Task $task
     * @return \Illuminate\Http\JsonResponse
     * @author 王凯
     */
    protected function video(Task $task, $classroom)
    {
        $me = auth('web')->user();

        // 对传递的时间进行进 1 取整
        $time = (int)ceil(request()->time);

        // 不存在则创建
        $result = $task->results()->where('user_id', $me->id)->first();

        if (!$result) {
            $result = new TaskResult(request()->all());
            $result->course_id = $task->course_id;
            $result->classroom_id = $classroom ? $classroom->classroom_id : 0;
            $result->plan_id = $task->plan_id;
            $result->task_id = $task->id;
            $result->user_id = $me->id;
            if ($time >= $task->length) {
                $result->status = TaskResultStatus::FINISH;
                $result->finished_at = now();
            }
            $result->save();
            $res = typeResult($task->chapter_id, $task->type, auth('web')->id());
            return ajax('200', '更新成功', ['status' => $res]);
        }
        // 存在且为完成状态，不做操作
        if ($result->status == TaskResultStatus::FINISH) {
            $res = typeResult($task->chapter_id, $task->type, auth('web')->id());
            return ajax('200', '更新成功', ['status' => $res]);
        }
        // 存在且为开始状态，执行更新
        if ($result->status == TaskResultStatus::START) {
            $result->time = $time;
            if ($time >= $task->length) {
                $result->status = TaskResultStatus::FINISH;
                $result->finished_at = now();
            }
            $result->save();
        }
        $res = typeResult($task->chapter_id, $task->type, auth('web')->id());

        return ajax('200', '更新成功', ['status' => $res]);
    }

    /**
     * PPT、Doc、Text 任务结果校验
     *
     * @param Task $task
     * @return \Illuminate\Http\JsonResponse
     * @author 王凯
     */
    protected function ppt(Task $task, $classroom)
    {
        $me = auth('web')->user();

        $result = $task->results()->where('user_id', $me->id)->exists();

        if (!$result) {
            // 创建学习记录
            $result = new TaskResult(request()->all());
            $result->course_id = $task->course_id;
            $result->classroom_id = $classroom ? $classroom->classroom_id : 0;
            $result->plan_id = $task->plan_id;
            $result->task_id = $task->id;
            $result->user_id = $me->id;
            $result->status = TaskResultStatus::FINISH;
            $result->finished_at = now();
            $result->save();
        }

        $res = typeResult($task->chapter_id, $task->type, auth('web')->id());

        return ajax('200', '更新成功', ['status' => $res]);
    }

    /**
     * 考试类任务结果校验
     *
     * @param Task $task
     * @return \Illuminate\Http\JsonResponse
     * @author 王凯
     */
    protected function test(Task $task, $classroom)
    {
        $me = auth('web')->user();

        // 不存在则创建
        $result = $task->results()->where('user_id', $me->id)->first();
        if (!$result) {
            // 创建学习记录
            $result = new TaskResult(request()->all());
            $result->course_id = $task->course_id;
            $result->classroom_id = $classroom ? $classroom->classroom_id : 0;
            $result->plan_id = $task->plan_id;
            $result->task_id = $task->id;
            $result->user_id = $me->id;

            $result->status = TaskResultStatus::FINISH;
            $result->finished_at = now();
            $result->save();
        }
    }

    protected function home(Task $task, $classroom)
    {
        $me = auth('web')->user();

        // 不存在则创建
        $result = $task->results()->where('user_id', $me->id)->first();
        if (!$result) {
            // 创建学习记录
            $result = new TaskResult(request()->all());
            $result->course_id = $task->course_id;
            $result->classroom_id = $classroom ? $classroom->classroom_id : 0;
            $result->plan_id = $task->plan_id;
            $result->task_id = $task->id;
            $result->user_id = $me->id;

            $result->status = TaskResultStatus::START;
            $result->finished_at = now();
            $result->save();
        }
    }

    /**
     * 弹题考试
     *
     * @param Task $task
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function videoPaper(Task $task, Request $request)
    {
        // 考试记录专用
        if ($task->target_type != 'video') {
            return ajax('400', '任务模式错误');
        }

        // 用户答题记录
        $answers = $request->questions;

        $me = auth('web')->user();

        $classroomMember = '';

        if ($cid = $request->cid) {
            $classroomMember = ClassroomMember::where(['user_id' => $me->id, 'classroom_id' => $cid])->first();
        }

        // 若为学员则查询用户是否在该教学版本之中
        $planMember = PlanMember::where('user_id', $me->id)->where('plan_id', $task->plan_id)->first();

        if (!$planMember && !$classroomMember) {
            return ajax('400', __('Not a member of the course.'));
        }

        // 获取试卷信息
        $paper = Paper::find($request->paper_id);
        if (!$paper) {
            return ajax('400', '试卷信息不存在');
        }

        // 获取试卷题目信息
        $questions = $paper->questions;
        if (!$questions->count()) {
            return ajax('400', '试卷题目信息不存在');
        }

        \DB::transaction(function () use ($task, $paper, $questions, $answers) {

            // 创建考试记录信息
            $paperResult = new PaperResult();

            $paperResult->task_id = $task->id;
            $paperResult->paper_title = $paper->title;
            $paperResult->paper_id = $paper->id;
            $paperResult->user_id = auth('web')->id();
            $paperResult->score = $paper->total_score;
            $paperResult->pass_score = $paper->pass_score;
            // 考试用时
            $paperResult->is_mark = 0;
            $paperResult->start_at = now();

            $paperResult->save();

            // 答题分数
            $answer_score = 0;
            $right_count = 0;
            $false_count = 0;
            $finished_count = 0;

            $answers = collect($answers)->keyBy('question_id');
            // 统计答题结果
            foreach ($questions as $question) {
                // 如果问题id存在用户答过的题id中则进行填充数据
                if ($question->id == $answers[$question->id]['question_id']) {
                    // 已答题数据统计
                    $finished_count++;

                    // 创建题目答题记录
                    $questionResult = new QuestionResult();

                    $questionResult->task_id = $task->id;
                    $questionResult->paper_result_id = $paperResult->id;
                    $questionResult->paper_id = $paper->id;
                    $questionResult->question_id = $question->id;
                    $questionResult->user_id = auth('web')->id();
                    // 主观题答案
                    $questionResult->objective_answer = json_encode($answers[$question->id]['answer']);
                    $questionResult->status = $question->type == ($answers[$question->id]['answer'] == $question->answers ? 'right' : 'error');
                    $questionResult->type = $question->type;
                    $questionResult->rate = $question->rate;
                    $questionResult->score = ($answers[$question->id]['answer'] == $question->answers ? $question->pivot->score : 0);

                    $questionResult->save();
                    // 正确错误答题统计，得分统计
                    if ($question->type != 'answer') {
                        if ($answers[$question->id]['answer'] == $question->answers) {
                            $right_count++;
                            $answer_score += $question->pivot->score;
                        } else {
                            $false_count++;
                        }
                    }
                }
            }
            // 选择题自动判卷
            $paperResult->answer_score = $answer_score;
            $paperResult->right_count = $right_count;
            $paperResult->false_count = $false_count;
            $paperResult->finished_count = $finished_count;
            $paperResult->plan_id = $task->plan->id;
            // 大于及格分数即为完成
            $paperResult->is_finished = $answer_score >= $paper->pass_score ? 1 : 0;
            $paperResult->end_at = now();
            $paperResult->is_mark = 1;
            // 更新试卷考试
            $paperResult->save();

        });

        return ajax('200', '提交成功');
    }
}
