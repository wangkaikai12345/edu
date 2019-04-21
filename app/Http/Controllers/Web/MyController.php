<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Transformers\ClassroomTransformer;
use App\Http\Transformers\CourseTransformer;
use App\Http\Transformers\NoteTransformer;
use App\Http\Transformers\PlanMemberTransformer;
use App\Http\Transformers\ReplyTransformer;
use App\Http\Transformers\TaskTransformer;
use App\Http\Transformers\TopicTransformer;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\Plan;
use App\Models\PlanMember;
use App\Models\PlanTeacher;
use App\Models\Task;
use App\Models\Topic;
use Illuminate\Http\Request;

class MyController extends Controller
{
    /**
     * @SWG\Tag(name="web/my",description="前台我的")
     */

    /**
     * @SWG\Get(
     *  path="/my-courses",
     *  tags={"web/my"},
     *  summary="我的学习",
     *  @SWG\Parameter(ref="#/parameters/page"),
     *  @SWG\Parameter(ref="#/parameters/per_page"),
     *  @SWG\Parameter(name="is_finished",in="query",type="string",enum={0,1},description="是否已完成所有任务"),
     *  @SWG\Parameter(name="include",in="query",type="string",description="是否包含关联数据：user,order,plan；或者嵌套加载：plan.course"),
     *  @SWG\Parameter(ref="#/parameters/sort"),
     *  @SWG\Response(response=200,ref="#/responses/PlanMemberPagination"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function courses(PlanMember $member)
    {
        $me = $this->auth()->user();

        $data = $member->filtered()->sorted()->where('user_id', $me->id)->paginate(self::perPage());

        return $this->response->paginator($data, new PlanMemberTransformer());
    }

    /**
     * @SWG\Get(
     *  path="/my-notes",
     *  tags={"web/my"},
     *  summary="我的笔记",
     *  @SWG\Parameter(ref="#/parameters/page"),
     *  @SWG\Parameter(ref="#/parameters/per_page"),
     *  @SWG\Parameter(in="query",name="content",type="string",description="内容"),
     *  @SWG\Parameter(in="query",name="course_id",type="string",description="课程ID"),
     *  @SWG\Parameter(in="query",name="user_id",type="string",description="用户ID"),
     *  @SWG\Parameter(in="query",name="plan_id",type="string",description="版本ID"),
     *  @SWG\Parameter(in="query",name="task_id",type="string",description="任务ID"),
     *  @SWG\Parameter(in="query",name="status",type="string",enum={"private","public"},description="内容"),
     *  @SWG\Parameter(name="include",in="query",type="string",description="是否包含关联数据：user,course,plan,task"),
     *  @SWG\Parameter(ref="#/parameters/sort"),
     *  @SWG\Response(response=200,ref="#/responses/CoursePagination"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function notes()
    {
        $me = auth()->user();

        $data = $me->notes()->filtered()->sorted()->paginate(self::perPage());

        return $this->response->paginator($data, new NoteTransformer());
    }


    /**
     * @SWG\Get(
     *  path="/my-replies",
     *  tags={"web/my"},
     *  summary="我的回复",
     *  @SWG\Parameter(ref="#/parameters/page"),
     *  @SWG\Parameter(ref="#/parameters/per_page"),
     *  @SWG\Parameter(name="include",in="query",type="string",description="是否包含关联数据：user,course,plan,task"),
     *  @SWG\Parameter(ref="#/parameters/sort"),
     *  @SWG\Response(response=200,ref="#/responses/ReplyPagination"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function replies()
    {
        $me = auth()->user();

        $data = $me->replies()->filtered()->sorted()->paginate(self::perPage());

        return $this->response->paginator($data, new ReplyTransformer());
    }

    /**
     * @SWG\Get(
     *  path="/my-tasks",
     *  tags={"web/my"},
     *  summary="我的任务",
     *  @SWG\Parameter(ref="#/parameters/page"),
     *  @SWG\Parameter(ref="#/parameters/per_page"),
     *  @SWG\Parameter(in="query",name="chapter_id",type="string",description="章节ID"),
     *  @SWG\Parameter(in="query",name="course_id",type="string",description="课程ID"),
     *  @SWG\Parameter(in="query",name="user_id",type="string",description="用户ID"),
     *  @SWG\Parameter(in="query",name="plan_id",type="string",description="版本ID"),
     *  @SWG\Parameter(in="query",name="task_id",type="string",description="任务ID"),
     *  @SWG\Parameter(in="query",name="title",type="string",description="任务ID"),
     *  @SWG\Parameter(in="query",name="is_free",type="integer",enum={0,1},description="是否免费"),
     *  @SWG\Parameter(in="query",name="is_optional",type="integer",enum={0,1},description="是否选修"),
     *  @SWG\Parameter(in="query",name="started_at",type="string",description="开始日期"),
     *  @SWG\Parameter(in="query",name="ended_at",type="string",description="结束日期"),
     *  @SWG\Parameter(in="query",name="status",type="string",enum={"draft","published","closed"},description="草稿、发布、关闭"),
     *  @SWG\Parameter(name="include",in="query",type="string",description="是否包含关联数据：chapter,results,user,target"),
     *  @SWG\Parameter(ref="#/parameters/sort"),
     *  @SWG\Response(response=200,ref="#/responses/TaskPagination"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function tasks(Task $task)
    {
        $data = $task->where('user_id', auth()->id())->filtered()->sorted()->paginate(self::perPage());

        return $this->response->paginator($data, new TaskTransformer());
    }

    /**
     * @SWG\Get(
     *  path="/my-teachings",
     *  tags={"web/my"},
     *  summary="在教课程/在教班级（由于是 join 查询，不支持通用排序，遂设置默认排序为 created_at,desc）",
     *  @SWG\Parameter(name="type",in="query",type="string",enum={"course","classroom"},description="在教课程、在教班级"),
     *  @SWG\Parameter(ref="#/parameters/page"),
     *  @SWG\Parameter(ref="#/parameters/per_page"),
     *  @SWG\Parameter(name="include",in="query",type="string",description="是否包含关联数据：user,category,default_plan,plans"),
     *  @SWG\Response(response=200,ref="#/responses/CoursePagination"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function teachings(Request $request)
    {
        $me = $this->auth()->user();

        // 根据
        switch ($request->type) {
            case 'classroom':
                $data = Classroom::join('classroom_teachers', 'classroom_teachers.classroom_id', '=', 'classrooms.id')
                    ->where('classroom_teachers.user_id', $me->id)
                    ->orderByDesc('classroom_teachers.created_at')
                    ->select('classrooms.id', 'classrooms.title', 'classrooms.cover', 'classrooms.origin_price', 'classrooms.price', 'classrooms.status')
                    ->paginate(self::perPage());

                return $this->response->paginator($data, new ClassroomTransformer());
                break;
            case 'course':
            default:
                $data = Course::join('plan_teachers', 'plan_teachers.plan_id', '=', 'courses.default_plan_id')
                    ->where('plan_teachers.user_id', '=', $me->id)
                    ->orderByDesc('plan_teachers.created_at')
                    ->select('courses.id', 'courses.title', 'courses.cover', 'courses.min_course_price', 'courses.max_course_price', 'courses.status')
                    ->paginate(self::perPage());

                return $this->response->paginator($data, new CourseTransformer());
        }
    }

    /**
     * @SWG\Get(
     *  path="/my-topics",
     *  tags={"web/my"},
     *  summary="我的话题/问答",
     *  @SWG\Parameter(ref="#/parameters/page"),
     *  @SWG\Parameter(ref="#/parameters/per_page"),
     *  @SWG\Parameter(in="query",name="user_id",type="string",description="用户ID"),
     *  @SWG\Parameter(in="query",name="course_id",type="string",description="课程ID"),
     *  @SWG\Parameter(in="query",name="plan_id",type="string",description="版本ID"),
     *  @SWG\Parameter(in="query",name="task_id",type="string",description="任务ID"),
     *  @SWG\Parameter(in="query",name="type",enum={"discussion","question","task"},type="string",description="类型"),
     *  @SWG\Parameter(in="query",name="is_stick",type="integer",enum={0,1},description="是否置顶"),
     *  @SWG\Parameter(in="query",name="is_elite",type="integer",enum={0,1},description="是否加精"),
     *  @SWG\Parameter(in="query",name="title",type="string",description="标题"),
     *  @SWG\Parameter(in="query",name="content",type="string",description="内容"),
     *  @SWG\Parameter(in="query",name="reply_num",type="string",description="回复次数"),
     *  @SWG\Parameter(in="query",name="hit_count",type="string",description="访问次数"),
     *  @SWG\Parameter(in="query",name="latest_reply_user_id",type="string",description="最新回复人"),
     *  @SWG\Parameter(name="include",in="query",type="string",description="是否包含关联数据：user,course,plan,task,replies,latest_replier"),
     *  @SWG\Parameter(ref="#/parameters/sort"),
     *  @SWG\Response(response=200,ref="#/responses/TopicPagination"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function topics(Topic $topic)
    {
        $data = $topic->where('user_id', auth()->id())->filtered()->sorted()->paginate(self::perPage());

        return $this->response->paginator($data, new TopicTransformer());
    }
}
