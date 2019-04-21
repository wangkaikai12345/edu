<?php

namespace App\Http\Controllers\Web;

use App\Enums\FavoriteType;
use App\Http\Controllers\Controller;
use App\Http\Transformers\CourseTransformer;
use App\Http\Transformers\FavoriteTransformer;
use App\Http\Transformers\FollowTransformer;
use App\Http\Transformers\NoteTransformer;
use App\Http\Transformers\PlanMemberTransformer;
use App\Http\Transformers\ReplyTransformer;
use App\Http\Transformers\TopicTransformer;
use App\Models\Course;
use App\Models\Favorite;
use App\Models\Plan;
use App\Models\PlanMember;
use App\Models\PlanTeacher;
use App\Models\User;

class HerController extends Controller
{
    /**
     * @SWG\Tag(name="web/her",description="用户/她的")
     */

    /**
     * @SWG\Get(
     *  path="/users/{user_id}/favorites",
     *  tags={"web/her"},
     *  summary="她的收藏课程",
     *  @SWG\Parameter(ref="#/parameters/page"),
     *  @SWG\Parameter(ref="#/parameters/per_page"),
     *  @SWG\Parameter(name="user_id",in="path",type="integer",required=true,description="用户ID"),
     *  @SWG\Parameter(name="include",in="query",type="string",description="是否包含关联数据：user"),
     *  @SWG\Response(response=200,ref="#/responses/FavoritePagination"),
     * )
     */
    public function favorites()
    {
        if (!request()->has('sort')) {
            request()->offsetSet('sort', 'created_at,desc');
        }

        $data = Favorite::where('model_type', FavoriteType::COURSE)->sorted()->paginate(self::perPage());

        return $this->response->paginator($data, (new FavoriteTransformer())->setDefaultIncludes(['model']));
    }

    /**
     * @SWG\Get(
     *  path="/users/{user_id}/courses",
     *  tags={"web/her"},
     *  summary="她的学习",
     *  @SWG\Parameter(ref="#/parameters/page"),
     *  @SWG\Parameter(ref="#/parameters/per_page"),
     *  @SWG\Parameter(name="user_id",in="path",type="integer",required=true,description="用户ID"),
     *  @SWG\Parameter(name="title",in="query",type="string",description="课程标题"),
     *  @SWG\Parameter(name="include",in="query",type="string",description="是否包含关联数据：user,order,plan；或者嵌套加载：plan.course"),
     *  @SWG\Parameter(ref="#/parameters/sort"),
     *  @SWG\Response(response=200,ref="#/responses/PlanMemberPagination"),
     * )
     */
    public function courses(User $user, PlanMember $member)
    {
        if (!request()->has('sort')) {
            request()->offsetSet('sort', 'created_at,desc');
        }

        // 默认展示课程
        request()->offsetSet('include', 'plan.course');

        $data = $member->filtered()->sorted()->where('user_id', $user->id)->paginate(self::perPage());

        return $this->response->paginator($data, new PlanMemberTransformer());
    }

    /**
     * @SWG\Get(
     *  path="/users/{user_id}/notes",
     *  tags={"web/her"},
     *  summary="她的笔记",
     *  @SWG\Parameter(ref="#/parameters/page"),
     *  @SWG\Parameter(ref="#/parameters/per_page"),
     *  @SWG\Parameter(in="path",name="user_id",type="integer",description="用户ID"),
     *  @SWG\Parameter(ref="#/parameters/NoteQuery-course_id"),
     *  @SWG\Parameter(ref="#/parameters/NoteQuery-course:title"),
     *  @SWG\Parameter(ref="#/parameters/NoteQuery-plan_id"),
     *  @SWG\Parameter(ref="#/parameters/NoteQuery-plan:title"),
     *  @SWG\Parameter(ref="#/parameters/NoteQuery-task_id"),
     *  @SWG\Parameter(ref="#/parameters/NoteQuery-task:title"),
     *  @SWG\Parameter(ref="#/parameters/NoteQuery-is_public"),
     *  @SWG\Parameter(ref="#/parameters/Note-sort"),
     *  @SWG\Parameter(ref="#/parameters/Note-include"),
     *  @SWG\Parameter(ref="#/parameters/page"),
     *  @SWG\Parameter(ref="#/parameters/per_page"),
     *  @SWG\Response(response=200,ref="#/responses/NotePagination"),
     * )
     */
    public function notes(User $user)
    {
        $data = $user->notes()->filtered()->sorted()->paginate(self::perPage());

        return $this->response->paginator($data, new NoteTransformer());
    }

    /**
     * @SWG\Get(
     *  path="/users/{user_id}/replies",
     *  tags={"web/her"},
     *  summary="她的回复",
     *  @SWG\Parameter(ref="#/parameters/page"),
     *  @SWG\Parameter(ref="#/parameters/per_page"),
     *  @SWG\Parameter(name="user_id",in="path",type="integer",description="用户ID"),
     *  @SWG\Parameter(name="content",in="query",type="string",description="回复内容"),
     *  @SWG\Parameter(name="include",in="query",type="string",description="是否包含关联数据：user,course,plan,task"),
     *  @SWG\Parameter(ref="#/parameters/sort"),
     *  @SWG\Response(response=200,ref="#/responses/ReplyPagination")
     * )
     */
    public function replies(User $user)
    {
        if (!request()->has('sort')) {
            request()->offsetSet('sort', 'created_at,desc');
        }

        $data = $user->replies()->filtered()->sorted()->paginate(self::perPage());

        return $this->response->paginator($data, new ReplyTransformer());
    }

    /**
     * @SWG\Get(
     *  path="/users/{user_id}/teachings",
     *  tags={"web/her"},
     *  summary="她的教学",
     *  @SWG\Parameter(ref="#/parameters/page"),
     *  @SWG\Parameter(ref="#/parameters/per_page"),
     *  @SWG\Parameter(name="user_id",in="path",type="integer",description="用户ID"),
     *  @SWG\Parameter(name="title",in="query",type="string",description="课程题目"),
     *  @SWG\Parameter(name="include",in="query",type="string",description="是否包含关联数据：user,category,default_plan,plans"),
     *  @SWG\Parameter(ref="#/parameters/sort"),
     *  @SWG\Response(response=200,ref="#/responses/CoursePagination"),
     * )
     */
    public function teachings(User $user, PlanTeacher $teacher, Plan $plan, Course $course)
    {
        if (!request()->has('sort')) {
            request()->offsetSet('sort', 'created_at,desc');
        }

        // 查询教师下参加所有的教学版本，通过教学版本获取所有课程信息
        $planIds = $teacher->where('user_id', $user->id)->pluck('plan_id');

        $courseIds = $plan->whereIn('id', $planIds)->pluck('course_id');

        $data = $course->whereIn('id', $courseIds)->filtered()->sorted()->paginate(self::perPage());

        return $this->response->paginator($data, new CourseTransformer());
    }

    /**
     * @SWG\Get(
     *  path="/users/{user_id}/topics",
     *  tags={"web/her"},
     *  summary="她的话题",
     *  @SWG\Parameter(ref="#/parameters/page"),
     *  @SWG\Parameter(ref="#/parameters/per_page"),
     *  @SWG\Parameter(name="user_id",in="path",type="integer",description="用户ID"),
     *  @SWG\Parameter(name="title",in="query",type="string",description="话题题目"),
     *  @SWG\Parameter(name="include",in="query",type="string",description="是否包含关联数据：user,course,plan,task,replies,latest_replier"),
     *  @SWG\Parameter(ref="#/parameters/sort"),
     *  @SWG\Response(response=200,ref="#/responses/TopicPagination"),
     * )
     */
    public function topics(User $user)
    {
        if (!request()->has('sort')) {
            request()->offsetSet('sort', 'created_at,desc');
        }

        $data = $user->topics()->filtered()->sorted()->paginate(self::perPage());

        return $this->response->paginator($data, new TopicTransformer());
    }

    /**
     * @SWG\Get(
     *  path="/users/{user_id}/followers",
     *  tags={"web/her"},
     *  summary="她的关注",
     *  @SWG\Parameter(in="path",name="user_id",type="string",description="用户ID"),
     *  @SWG\Parameter(ref="#/parameters/FollowQuery-follow_id"),
     *  @SWG\Parameter(ref="#/parameters/FollowQuery-follow:username"),
     *  @SWG\Parameter(ref="#/parameters/FollowQuery-is_pair"),
     *  @SWG\Parameter(ref="#/parameters/Follow-sort"),
     *  @SWG\Parameter(ref="#/parameters/Follow-include"),
     *  @SWG\Parameter(ref="#/parameters/page"),
     *  @SWG\Parameter(ref="#/parameters/per_page"),
     *  @SWG\Response(response=200,ref="#/responses/FollowPagination"),
     * )
     */
    public function followers(User $user)
    {
        $data = $user->followers()->filtered()->sorted()->paginate(self::perPage());

        return $this->response->paginator($data, (new FollowTransformer())->setDefaultIncludes(['follow']));
    }

    /**
     * @SWG\Get(
     *  path="/users/{user_id}/fans",
     *  tags={"web/her"},
     *  summary="她的粉丝",
     *  @SWG\Parameter(in="path",name="user_id",type="string",description="用户ID"),
     *  @SWG\Parameter(ref="#/parameters/FollowQuery-user_id"),
     *  @SWG\Parameter(ref="#/parameters/FollowQuery-user:username"),
     *  @SWG\Parameter(ref="#/parameters/FollowQuery-is_pair"),
     *  @SWG\Parameter(ref="#/parameters/Follow-sort"),
     *  @SWG\Parameter(ref="#/parameters/Follow-include"),
     *  @SWG\Parameter(ref="#/parameters/page"),
     *  @SWG\Parameter(ref="#/parameters/per_page"),
     *  @SWG\Response(response=200,ref="#/responses/FollowPagination"),
     * )
     */
    public function fans(User $user)
    {
        $data = $user->fans()->filtered()->sorted()->paginate(self::perPage());

        return $this->response->paginator($data, (new FollowTransformer())->setDefaultIncludes(['user']));
    }
}