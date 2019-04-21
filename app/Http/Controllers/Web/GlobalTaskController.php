<?php

namespace App\Http\Controllers\Web;

use App\Enums\StudentStatus;
use App\Http\Controllers\Controller;
use App\Http\Transformers\TaskTransformer;
use App\Models\ClassroomCourse;
use App\Models\ClassroomMember;
use App\Models\Plan;
use App\Models\PlanMember;
use App\Models\PlanTeacher;

class GlobalTaskController extends Controller
{
    /**
     * @SWG\Tag(name="web/task",description="前台任务")
     */

    /**
     * @SWG\Get(
     *  path="/tasks",
     *  tags={"web/task"},
     *  summary="全部任务列表",
     *  description="用于普通用户展示，仅展示已发布任务；而且必须传递一个参数，否则返回为空。",
     *  @SWG\Parameter(in="query",name="plan_id",type="string",description="版本ID"),
     *  @SWG\Parameter(in="query",name="mode",type="string",enum={"task","preparation","exercise","homework","extra_class"},default=null,description="任务模式 代表 task 任务 preparation 预习 exercise 练习 homework 作业 extra_class 课外"),
     *  @SWG\Parameter(name="include",in="query",type="string",description="是否包含关联数据：chapter,result,user,target"),
     *  @SWG\Parameter(ref="#/parameters/sort"),
     *  @SWG\Response(response=200,ref="#/responses/TaskResponse"),
     * )
     */
    public function index()
    {
        request()->offsetSet('sort', 'seq,asc');

        $plan = Plan::findOrFail(request('plan_id'));

        $data = $plan->getSortedTasks();

        $me = auth()->user();

        // 根据版本或班级查询购买情况
        $isBought = $me && PlanMember::where('user_id', $me->id)->where('plan_id', request('plan_id'))->normal()->exists();

        // 若未购买版本，查看是否购买班级
        if (!$isBought) {
            $isBought = ClassroomMember::normal()->join('classroom_courses as courses', 'classroom_members.classroom_id', '=', 'courses.classroom_id')
                ->where('classroom_members.user_id', $me->id)
                ->where('courses.plan_id', request('plan_id'))
                ->exists();
        }

        // 是否控制课程
        $isControl = false;
        if ($me->isAdmin()) {
            $isControl = true;
        }
        if (PlanTeacher::where('user_id', $me->id)->where('plan_id', $plan->id)->exists()) {
            $isControl = true;
        }

        return $this->response->collection($data, (new TaskTransformer())->setDefaultIncludes(['target']))->setMeta([
            'is_bought' => $isBought,
            'is_control' => $isControl
        ]);
    }
}