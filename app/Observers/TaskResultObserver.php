<?php

namespace App\Observers;

use App\Enums\TaskResultStatus;
use App\Enums\TaskType;
use App\Models\Classroom;
use App\Models\ClassroomCourse;
use App\Models\ClassroomMember;
use App\Models\ClassroomResult;
use App\Models\Course;
use App\Models\HomeworkPost;
use App\Models\PlanMember;
use App\Models\TaskResult;

class TaskResultObserver
{
    /**
     * 监听任务结果更新状态
     *
     * @param TaskResult $result
     */
    public function saved(TaskResult $result)
    {
        // 如果是班级课程的进度更新，同步更新班级成员学习记录
        if ($result->classroom_id) {
            $classroom = Classroom::find($result->classroom_id);

            if (!$classroom) {
                return;
            }

            // 班级成员数据
            $classroomMember = ClassroomMember::where(['classroom_id'=>$classroom->id, 'user_id'=>$result->user_id])->first();

            if (!$classroomMember) {
                return;
            }

            // 获取更新的任务
            $task = $result->task;

            // 班级课程学习记录
            $classroomResult = ClassroomResult::where(
                [
                    'classroom_id' => $classroom->id,
                    'plan_id' => $result->plan_id,
                    'user_id' => $result->user_id,
                    'chapter_id' => $task->chapter_id,
                ]
            )->first();

            if ($classroomResult) {
                // 更新操作
                $classroomResult->progress = array_search($task->type, array_keys(TaskType::toSelectArray()))+1;

                // 如果更新的是作业，状态为待审批
                if ($task->type == 'd-homework' && $result->status == 'start'){
                    $classroomResult->status = 'approval';
                }

                if ($task->type == 'd-homework' && $result->status == 'finish'){

                    $score = HomeworkPost::where(
                        [
                            'classroom_id' => $classroom->id,
                            'task_id' => $task->id,
                            'user_id' => $result->user_id,
                            'locked' => 'open',
                        ]
                    )->first();

                    $classroomResult->score = $score ? ($classroomResult->score ? ($score->result + $classroomResult->score)/2 : $score->result) : ($classroomResult->score ?? 0);
                }

                // 如果通关，重新统计平均分，统计分数
                if (typeResult($task->chapter_id, 'd-homework', $result->user_id) == 100){

                    $classroomResult->status = 'pass';

                    $average_score = ClassroomResult::where(
                        [
                            'classroom_id' => $classroom->id,
                            'plan_id' => $result->plan_id,
                            'user_id' => $result->user_id,
                            'status' => 'pass'
                        ]
                    )->get()->avg('score');
                }

                $classroomResult->save();
            } else {

                //  新建本节的学习记录
                $classroomResult = new ClassroomResult();
                $classroomResult->classroom_id = $classroom->id;
                $classroomResult->user_id = $result->user_id;
                $classroomResult->plan_id = $result->plan_id;
                $classroomResult->chapter_id = $task->chapter_id;
                $classroomResult->progress = 1;
                $classroomResult->score = 0;
                $classroomResult->time = 0;
                $classroomResult->status = 'learn';

                $classroomResult->save();
            }

            // 获取班级的所有关
            $chaps = $classroom->chaps();

            // 通关
            if (typeResult($task->chapter_id, 'd-homework', $result->user_id) == 100) {

                if ($classroomMember->current_chap == $task->chapter_id) {

                    $classroomMember->pass_count++;

                    $new = $chaps[$chaps->search($task->chapter)+1];

                    $classroomMember->current_chap = $new->id;
                }

            }

            if ($chaps->last()->id == $task->chapter_id) {
                $classroomMember->status = 'finished';
                $classroomMember->finished_at = now();
            } else {
                $classroomMember->status = 'learning';
            }

            $classroomMember->last_learned_at = now();

            $classroomMember->average_score = !empty($average_score) ? $average_score : 0;

            $classroomMember->save();
        }

        $member = PlanMember::where('plan_id', $result->plan_id)
            ->where('user_id', $result->user_id)
            ->first();
        if ($member) {
            $this->makeResult($result, $member);
        }
    }

    private function makeResult(TaskResult $result, $member)
    {
        // 如果学完当前课程，更新已学任务数量
        if ($result->status === TaskResultStatus::FINISH) {
            !$result->task->is_optional && $member->learned_compulsory_count++;
            $member->learned_count++;
        }

        // 判断任务是否完成
        if ($member->learned_compulsory_count == $result->plan->tasks_count) {
            $member->is_finished = true;
            $member->finished_at = now();
        }
        $member->last_learned_at = now();
        $member->save();
    }
}