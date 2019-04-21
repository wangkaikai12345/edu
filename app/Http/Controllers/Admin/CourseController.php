<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Status;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Transformers\CourseTransformer;
use App\Models\Course;

class CourseController extends Controller
{
    /**
     * @SWG\Tag(name="admin/course",description="课程")
     */

    /**
     * @SWG\Get(
     *  path="/admin/courses",
     *  tags={"admin/course"},
     *  summary="列表",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(ref="#/parameters/page"),
     *  @SWG\Parameter(ref="#/parameters/per_page"),
     *  @SWG\Parameter(ref="#/parameters/CourseQuery-title"),
     *  @SWG\Parameter(ref="#/parameters/CourseQuery-category_id"),
     *  @SWG\Parameter(ref="#/parameters/CourseQuery-category:name"),
     *  @SWG\Parameter(ref="#/parameters/CourseQuery-status"),
     *  @SWG\Parameter(ref="#/parameters/CourseQuery-serialize_mode"),
     *  @SWG\Parameter(ref="#/parameters/CourseQuery-user_id"),
     *  @SWG\Parameter(ref="#/parameters/CourseQuery-user:username"),
     *  @SWG\Parameter(ref="#/parameters/CourseQuery-is_recommended"),
     *  @SWG\Parameter(ref="#/parameters/CourseQuery-recommended_seq"),
     *  @SWG\Parameter(ref="#/parameters/CourseQuery-recommended_at"),
     *  @SWG\Parameter(ref="#/parameters/CourseQuery-min_course_price"),
     *  @SWG\Parameter(ref="#/parameters/CourseQuery-max_course_price"),
     *  @SWG\Parameter(ref="#/parameters/CourseQuery-reviews_count"),
     *  @SWG\Parameter(ref="#/parameters/CourseQuery-rating"),
     *  @SWG\Parameter(ref="#/parameters/CourseQuery-notes_count"),
     *  @SWG\Parameter(ref="#/parameters/CourseQuery-students_count"),
     *  @SWG\Parameter(ref="#/parameters/CourseQuery-hit_count"),
     *  @SWG\Parameter(ref="#/parameters/CourseQuery-materials_count"),
     *  @SWG\Parameter(ref="#/parameters/Course-sort"),
     *  @SWG\Parameter(ref="#/parameters/Course-include"),
     *  @SWG\Parameter(ref="#/parameters/page"),
     *  @SWG\Parameter(ref="#/parameters/per_page"),
     *  @SWG\Response(response=200,ref="#/responses/CoursePagination"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function index(Course $course)
    {
        $data = Course::filtered()->sorted()->withCount(['plans'])->paginate(self::perPage());

        // 课程统计信息
        $stat = [];
        $stat['total'] = $course->count();
        $stat['published'] = $course->published()->count();
        $stat['closed'] = $course->closed()->count();
        $stat['draft'] = $course->draft()->count();

        return $this->response->paginator($data, new CourseTransformer())->setMeta($stat);
    }

    /**
     * @SWG\Delete(
     *  path="/admin/courses/{course_id}",
     *  tags={"admin/course"},
     *  summary="删除",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(in="path",name="course_id",type="integer",required=true,description="课程ID"),
     *  @SWG\Response(response=204,description="ok"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function destroy(Course $course)
    {
        $course->delete();

        return $this->response->noContent();
    }

    /**
     * @SWG\Patch(
     *  path="/admin/courses/{course_id}/recommend",
     *  tags={"admin/course"},
     *  summary="推荐与取消",
     *  description="推荐序数降序排名",
     *  produces={"application/json"},
     *  @SWG\Parameter(in="path",name="course_id",type="integer",required=true),
     *  @SWG\Parameter(ref="#/parameters/CourseForm-is_recommended"),
     *  @SWG\Parameter(ref="#/parameters/CourseForm-recommended_seq"),
     *  @SWG\Response(response=204,description="ok"),
     *  security={
     *      {"Bearer": {}}
     *  }
     * )
     */
    public function recommend(Request $request, Course $course)
    {
        // 数据验证
        $this->validate($request, [
            'is_recommended' => 'required|bool',
            'recommended_seq' => 'sometimes|integer|min:0|max:9999'
        ]);

        $course->is_recommended = $request->is_recommended;
        $seq = $request->recommended_seq ?? 0;
        $request->is_recommended ? $course->recommended_seq = $seq : $course->recommended_seq = 0;
        $request->is_recommended && $course->recommended_at = now();
        $course->save();

        return $this->response->noContent();
    }

    /**
     * @SWG\Patch(
     *  path="/admin/courses/{course_id}/publish",
     *  tags={"admin/course"},
     *  summary="发布与关闭",
     *  description="发布课程时，当默认版本未发布时，自动发布；关闭课程时，已发布版本不受不影响。",
     *  produces={"application/json"},
     *  @SWG\Parameter(in="path",name="course_id",type="integer",required=true,description="课程ID"),
     *  @SWG\Parameter(ref="#/parameters/CourseForm-status"),
     *  @SWG\Response(response=204,description="ok"),
     *  security={
     *      {"Bearer": {}}
     *  }
     * )
     */
    public function publish(Course $course, Request $request)
    {
        $this->validate($request, [
            'status' => 'required|in:published,closed'
        ]);

        // 当 发布 课程时，默认版本同时发布；当 关闭 时，仅关闭课程。
        $default_plan = $course->default_plan;
        if ($request->status == Status::PUBLISHED) {
            switch ($default_plan->status) {
                case Status::DRAFT:
                case Status::CLOSED:
                    DB::transaction(function () use ($course, $default_plan) {
                        $course->status = Status::PUBLISHED;
                        $course->save();
                        $default_plan->status = Status::PUBLISHED;
                        $default_plan->save();
                    });
                    break;
                case Status::PUBLISHED:
                    $course->status = Status::PUBLISHED;
                    $course->save();
                    break;
            }
        } else {
            $course->status = Status::CLOSED;
            $course->save();
        }

        return $this->response->noContent();
    }
}