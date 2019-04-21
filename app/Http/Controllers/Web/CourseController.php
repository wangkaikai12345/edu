<?php

namespace App\Http\Controllers\Web;

use App\Enums\Status;
use App\Events\CourseViewEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\CourseRequest;
use App\Http\Transformers\CourseTransformer;
use App\Models\Category;
use App\Models\Course;
use App\Models\Favorite;
use App\Models\PlanTeacher;
use App\Models\User;
use App\Models\Plan;
use DB;
use Dingo\Api\Http\Request;

class CourseController extends Controller
{
    /**
     * @SWG\Tag(name="web/course",description="课程")
     */

    /**
     * @SWG\Get(
     *  path="/courses",
     *  tags={"web/course"},
     *  summary="课程列表（普通用户）",
     *  description="已发布课程",
     *  @SWG\Parameter(ref="#/parameters/CourseQuery-title"),
     *  @SWG\Parameter(ref="#/parameters/CourseQuery-category_id"),
     *  @SWG\Parameter(ref="#/parameters/CourseQuery-category:name"),
     *  @SWG\Parameter(ref="#/parameters/CourseQuery-serialize_mode"),
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
     *  @SWG\Response(response=200,description="ok",ref="#/responses/CoursePagination"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function index(Request $request, Course $course)
    {
        $data = Course::notCopy()->where('status', Status::PUBLISHED)->filtered()->sorted()->paginate(self::perPage());

        return $this->response->paginator($data, new CourseTransformer());
    }

    /**
     * @SWG\Get(
     *  path="/courses/{course_id}",
     *  tags={"web/course"},
     *  summary="课程详情（普通用户）",
     *  description="默认仅展示已发布内容",
     *  @SWG\Parameter(name="course_id",type="integer",in="path",required=true,description="课程ID"),
     *  @SWG\Parameter(ref="#/parameters/Course-include"),
     *  @SWG\Response(response=200,description="ok",ref="#/definitions/Course"),
     *  @SWG\Response(response=400,description="课程已关闭。"),
     *  @SWG\Response(response=404,description="未找到课程"),
     * )
     */
    public function show($course)
    {
        $course = Course::findOrFail($course);
        if ($course->status === Status::CLOSED) {
            $this->response->errorBadRequest(Status::getDescription(Status::CLOSED));
        }
        if ($course->status === Status::DRAFT) {
            $this->response->errorBadRequest(Status::getDescription(Status::DRAFT));
        }

        return $this->response->item($course, new CourseTransformer());
    }
}