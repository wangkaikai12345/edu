<?php

namespace App\Http\Controllers\Web;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Transformers\QuestionTransformer;
use App\Models\ClassroomMember;
use App\Models\PlanMember;
use App\Models\QuestionResult;
use App\Models\Task;
use App\Models\TestResult;

class TestController extends Controller
{
    /**
     * @SWG\Get(
     *  path="/tasks/{task_id}/start-test",
     *  tags={"web/test"},
     *  summary="开始考试（获取考试题目）",
     *  description="开始考试动作，此步骤生成考试记录（考试唯一入口）；当完成考试时，会返回题目信息，以及答题记录；新增 meta 信息，status=created新创建|ongoing答题中|finished已完成",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="task_id",in="path",type="string",required=true,description="任务ID"),
     *  @SWG\Response(response=200,description="考试已完成，返回题目的同时，也会返回答题记录",ref="#/responses/QuestionResponse"),
     *  @SWG\Response(response=201,description="考试未完成，仅返回简版的题目信息",ref="#/responses/QuestionResponse"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function start(Task $task)
    {
        if ($task->status !== Status::PUBLISHED) {
            $this->response->errorForbidden(__('Unpublished.'));
        }

        $test = $task->target()->firstOrFail();

        // 判断是否为版本成员、班级成员
        $isClassroomMember = ClassroomMember::normal()->join('classroom_courses as courses', 'classroom_members.classroom_id', '=', 'courses.classroom_id')
            ->where('classroom_members.user_id', auth()->id())
            ->where('courses.plan_id', $task->plan_id)
            ->exists();
        if (!$isClassroomMember && !($isPlanMember = PlanMember::where('plan_id', $task->plan_id)->where('user_id', auth()->id())->exists())) {
            $this->response->errorForbidden(__('Not a member of the course.'));
        }

        // 重复获取试卷时，不再添加记录
        $testResult = TestResult::where('task_id', $task->id)->where('test_id', $test->id)->where('user_id', auth()->id())->first();
        if (!$testResult) {
            // 查询题目，并隐藏非必要信息
            $questions = $test->questions()->select(['id', 'title', 'type', 'options'])->get();
            TestResult::create([
                'task_id' => $task->id,
                'test_id' => $test->id,
                'user_id' => auth()->id(),
                'right_count' => 0,
                'questions_count' => $test->questions_count,
                'is_finished' => 0,
                'finished_count' => 0,
                'score' => 0,
                'total_score' => $test->total_score,
            ]);
            return $this->response->collection($questions, new QuestionTransformer())
                ->setStatusCode(201)
                ->setMeta([
                    'status' => 'created'
                ]);
        } // 当试卷为未完成状态
        else if (!$testResult->is_finished) {
            // 查询题目，并隐藏非必要信息
            $questions = $test->questions()->select(['id', 'title', 'type', 'options'])->get();
            $questions->map(function ($item) use ($task) {
                $result = QuestionResult::where('task_id', $task->id)
                    ->where('user_id', auth()->id())
                    ->where('question_id', $item->id)
                    ->first(['question_id', 'answers', 'is_right', 'score']);
                $item->result = $result;
                return $item;
            });

            return $this->response->collection($questions, new QuestionTransformer())->setMeta([
                'status' => 'ongoing',
            ]);
        } // 当试卷为已完成状态时，返回完整考题信息、并获取用户的答题记录信息
        else {
            $questions = $test->questions()->get();
            $questions->map(function ($item) use ($task) {
                $result = QuestionResult::where('task_id', $task->id)
                    ->where('user_id', auth()->id())
                    ->where('question_id', $item->id)
                    ->first(['question_id', 'answers', 'is_right', 'score']);
                $item->result = $result;
                return $item;
            });

            return $this->response->collection($questions, new QuestionTransformer())->setMeta([
                'status' => 'finished',
            ]);
        }
    }
}
