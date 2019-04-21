<?php

namespace App\Http\Controllers\Web;

use App\Enums\TaskResultStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\QuestionResultRequest;
use App\Models\ClassroomMember;
use App\Models\PlanMember;
use App\Models\QuestionResult;
use App\Models\Task;
use App\Models\TaskResult;
use App\Models\TestResult;

class QuestionResultController extends Controller
{
    // 标签
    /**
     * @SWG\Tag(name="web/test",description="考试答题记录")
     */

    /**
     * @SWG\Post(
     *  path="/tasks/{task_id}/results",
     *  tags={"web/test"},
     *  summary="提交答案",
     *  description="此步骤会自动生成答题记录、考试记录以及任务完成记录，自动更新已完成任务数等",
     *  @SWG\Parameter(in="path",name="task_id",type="integer",description="任务ID"),
     *  @SWG\Parameter(ref="#/parameters/QuestionResultForm-question_id"),
     *  @SWG\Parameter(ref="#/parameters/QuestionResultForm-answers"),
     *  @SWG\Response(response=201,description="ok",@SWG\Schema(
     *     @SWG\Property(property="is_right",type="boolean",description="是否正确",example=true),
     *     @SWG\Property(property="is_finished",type="boolean",description="是否完成",example=true),
     *  )),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function store(Task $task, QuestionResultRequest $request)
    {
        $userId = auth()->id();

        // 判断用户是否为该版本学员、班级学员
        $isClassroomMember = ClassroomMember::normal()->join('classroom_courses as courses', 'classroom_members.classroom_id', '=', 'courses.classroom_id')
            ->where('classroom_members.user_id', auth()->id())
            ->where('courses.plan_id', $task->plan_id)
            ->exists();

        if (!$isClassroomMember && !($isPlanMember = PlanMember::where('user_id', $userId)->where('plan_id', $task->plan_id)->exists())) {
            $this->response->errorForbidden(__('Not a member of the course.'));
        }

        // 查询是否存在考试记录（正常进入则会存在）
        $test = $task->target()->firstOrFail();
        if (!$test->results()->where('user_id', $userId)->exists()) {
            $this->response->errorForbidden(__('Please start in the normal way.'));
        }

        // 获取用户并查看是否已经答过
        if (QuestionResult::where('user_id', $userId)
            ->where('task_id', $task->id)
            ->where('question_id', $request->question_id)
            ->where('test_id', $test->id)->exists()) {
            $this->response->errorBadRequest(__('Repeat submitting.'));
        }

        // 获取题目正确答案
        $question = $test->questions()
            ->where('question_id', $request->question_id)
            ->firstOrFail();

        // 排序前者
        $requestAnswers = $request->answers;
        sort($requestAnswers);
        $isRight = $question->answers === $requestAnswers;
        $score = $isRight ? (integer)$question->pivot->score : 0;

        // 查询任务记录
        $taskResult = $task->results()->where('user_id', $userId)->first();

        $testResult = TestResult::where('task_id', $task->id)->where('test_id', $test->id)->where('user_id', $userId)->first();
        if (!$testResult) {
            abort(400, __('Please start in the normal way.'));
        }

        // 添加答题记录、计入本次考试成绩
        $testResult = \DB::transaction(function () use ($userId, $test, $request, $requestAnswers, $score, $isRight, $task, $taskResult, $testResult) {
            $questionResult = QuestionResult::create([
                'task_id' => $task->id,
                'test_id' => $test->id,
                'question_id' => $request->question_id,
                'user_id' => $userId,
                'answers' => $requestAnswers,
                'score' => $score,
                'is_right' => $isRight
            ]);

            $testResult->score += $questionResult->score;
            $questionResult->is_right && $testResult->right_count++;
            $testResult->finished_count++;
            // 判断是否完成
            if ($testResult->finished_count == $testResult->questions_count) {
                $testResult->is_finished = 1;
            }
            $testResult->save();

            // 当答题完成时，创建或更新答题记录 TaskResult
            if ($taskResult && $testResult->is_finished) {
                $taskResult->status = TaskResultStatus::FINISH;
                $taskResult->finished_at = now();
                $taskResult->save();
            } else if (!$taskResult) {
                $taskResult = new TaskResult();
                $taskResult->plan_id = $task->plan_id;
                $taskResult->task_id = $task->id;
                $taskResult->user_id = $userId;
                if ($testResult->is_finished) {
                    $taskResult->status = TaskResultStatus::FINISH;
                    $taskResult->finished_at = now();
                } else {
                    $taskResult->status = TaskResultStatus::START;
                }
                $taskResult->save();
            }

            return $testResult;
        });

        return $this->response->array([
            'is_right' => $isRight,
            'is_finished' => (bool)$testResult->is_finished
        ])->setStatusCode(201);
    }
}
