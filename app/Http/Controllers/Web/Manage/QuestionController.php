<?php

namespace App\Http\Controllers\Web\Manage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\QuestionRequest;
use App\Http\Transformers\QuestionTransformer;
use App\Models\Course;
use App\Models\Question;

class QuestionController extends Controller
{
    /**
     * @SWG\Tag(name="web/question",description="题目")
     */

    /**
     * @SWG\GET(
     *  path="/manage/courses/{course_id}/questions",
     *  tags={"web/question"},
     *  summary="题目列表",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(ref="#/parameters/QuestionQuery-title"),
     *  @SWG\Parameter(ref="#/parameters/QuestionQuery-type"),
     *  @SWG\Parameter(ref="#/parameters/QuestionQuery-difficulty"),
     *  @SWG\Parameter(ref="#/parameters/QuestionQuery-course_id"),
     *  @SWG\Parameter(ref="#/parameters/QuestionQuery-course:title"),
     *  @SWG\Parameter(ref="#/parameters/QuestionQuery-plan_id"),
     *  @SWG\Parameter(ref="#/parameters/QuestionQuery-plan:title"),
     *  @SWG\Parameter(ref="#/parameters/QuestionQuery-chapter_id"),
     *  @SWG\Parameter(ref="#/parameters/QuestionQuery-chapter:title"),
     *  @SWG\Parameter(ref="#/parameters/QuestionQuery-user_id"),
     *  @SWG\Parameter(ref="#/parameters/QuestionQuery-user:username"),
     *  @SWG\Parameter(ref="#/parameters/Question-sort"),
     *  @SWG\Parameter(ref="#/parameters/Question-include"),
     *  @SWG\Parameter(ref="#/parameters/page"),
     *  @SWG\Parameter(ref="#/parameters/per_page"),
     *  @SWG\Response(response=200,ref="#/responses/QuestionPagination"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function index(Course $course)
    {
        $this->authorize('isCourseTeacher', $course);

        $paginator = $course->questions()->filtered()->sorted()->paginate(self::perPage());

        return $this->response->paginator($paginator, new QuestionTransformer());
    }

    /**
     * @SWG\Post(
     *  path="/manage/courses/{course_id}/questions",
     *  tags={"web/question"},
     *  summary="题目添加",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="course_id",in="path",type="integer",required=true,description="课程ID"),
     *  @SWG\Parameter(ref="#/parameters/QuestionForm-title"),
     *  @SWG\Parameter(ref="#/parameters/QuestionForm-type"),
     *  @SWG\Parameter(ref="#/parameters/QuestionForm-options"),
     *  @SWG\Parameter(ref="#/parameters/QuestionForm-answers"),
     *  @SWG\Parameter(ref="#/parameters/QuestionForm-plan_id"),
     *  @SWG\Parameter(ref="#/parameters/QuestionForm-chapter_id"),
     *  @SWG\Parameter(ref="#/parameters/QuestionForm-difficulty"),
     *  @SWG\Parameter(ref="#/parameters/QuestionForm-explain"),
     *  @SWG\Response(response=201,description="ok",ref="#/definitions/Question"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function store(QuestionRequest $request, Course $course, Question $question)
    {
        $this->authorize('isCourseTeacher', $course);

        $question->fill($request->all());
        $question->course_id = $course->id;
        $question->user_id = auth()->id();
        $question->save();

        return $this->response->item($question, new QuestionTransformer())->setStatusCode(201);
    }

    /**
     * @SWG\Put(
     *  path="/manage/courses/{course_id}/questions/{question_id}",
     *  tags={"web/question"},
     *  summary="题目更新",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="course_id",in="path",type="integer",required=true,description="课程"),
     *  @SWG\Parameter(ref="#/parameters/QuestionForm-title"),
     *  @SWG\Parameter(ref="#/parameters/QuestionForm-type"),
     *  @SWG\Parameter(ref="#/parameters/QuestionForm-options"),
     *  @SWG\Parameter(ref="#/parameters/QuestionForm-answers"),
     *  @SWG\Parameter(ref="#/parameters/QuestionForm-plan_id"),
     *  @SWG\Parameter(ref="#/parameters/QuestionForm-chapter_id"),
     *  @SWG\Parameter(ref="#/parameters/QuestionForm-difficulty"),
     *  @SWG\Parameter(ref="#/parameters/QuestionForm-explain"),
     *  @SWG\Response(response=204,description="ok"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function update(QuestionRequest $request, Course $course, $question)
    {
        $question = $course->questions()->findOrFail($question);

        $this->authorize('isCourseTeacher', $course);

        $question->fill(array_only($request->all(), ['title', 'options', 'type', 'answers', 'plan_id', 'chapter_id', 'difficulty', 'explain']));
        $question->save();

        return $this->response->noContent();
    }

    /**
     * @SWG\Delete(
     *  path="/manage/courses/{course_id}/questions/{question_id}",
     *  tags={"web/question"},
     *  summary="题目删除",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="course_id",in="path",type="integer",required=true,description="课程"),
     *  @SWG\Parameter(name="question_id",in="path",type="integer",required=true,description="题目"),
     *  @SWG\Response(response=204,description="ok"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function destroy(Course $course, $question)
    {
        $question = $course->questions()->findOrFail($question);

        $this->authorize('isCourseTeacher', $course);

        $question->delete();

        return $this->response->noContent();
    }
}
