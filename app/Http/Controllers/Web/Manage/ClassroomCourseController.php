<?php

namespace App\Http\Controllers\Web\Manage;

use App\Http\Transformers\ClassroomCourseTransformer;
use App\Models\Chapter;
use App\Models\Classroom;
use App\Http\Controllers\Controller;
use App\Models\ClassroomCourse;
use App\Models\Course;
use App\Models\Plan;
use App\Models\Question;
use App\Models\QuestionResult;
use App\Models\Task;
use App\Models\TaskResult;
use App\Models\Test;
use App\Models\TestQuestion;
use App\Models\TestResult;
use DB;
use Illuminate\Http\Request;

class ClassroomCourseController extends Controller
{
    /**
     * 迁移对照表
     */
    public $courseHash = [];
    public $planHash = [];
    public $chapterHash = [];
    public $taskHash = [];
    public $taskResultHash = [];
    public $questionHash = [];
    public $testHash = [];
    public $testResultHash = [];

    /**
     * @SWG\Get(
     *  path="/manage/classrooms/{classroom_id}/courses",
     *  tags={"web/classroom"},
     *  summary="班级课程列表",
     *  description="",
     *  @SWG\Parameter(name="classroom_id",type="integer",in="path",required=true,description="班级"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomCourseQuery-is_synced"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomCourseQuery-course:title"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomCourseQuery-created_at"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomCourse-include"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomCourse-sort"),
     *  @SWG\Response(response=200,description="ok",ref="#/responses/ClassroomCourseResponse"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function index(Classroom $classroom)
    {
        $data = ClassroomCourse::where('classroom_id', $classroom->id)->filtered()->sorted()->get();

        return $this->response->collection($data, new ClassroomCourseTransformer());
    }

    /**
     * @SWG\Post(
     *  path="/manage/classrooms/{classroom_id}/courses",
     *  tags={"web/classroom"},
     *  summary="添加",
     *  description="仅班级教师允许",
     *  @SWG\Parameter(ref="#/parameters/ClassroomCourseForm-course_id"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomCourseForm-plan_id"),
     *  @SWG\Response(response=201,description="ok",ref="#/definitions/ClassroomCourse"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function store(Request $request, Classroom $classroom)
    {
        $this->authorize('isClassroomTeacher', $classroom);

        $plan = Plan::where('course_id', $request->course_id)->findOrFail($request->plan_id);

        $exists = ClassroomCourse::where('course_id', $plan->course_id)->exists();
        if ($exists) {
            $this->response->errorBadRequest(__('Classroom can only have a plan in the course.'));
        }

        $item = new ClassroomCourse();
        $item->course_id = $plan->course_id;
        $item->plan_id = $plan->id;
        $item->classroom_id = $classroom->id;
        $item->save();

        return $this->response->item($item, new ClassroomCourseTransformer())->setStatusCode(201);
    }

    /**
     * @SWG\Put(
     *  path="/manage/classrooms/{classroom_id}/courses/{course_id}",
     *  tags={"web/classroom"},
     *  summary="取消同步",
     *  description="仅班级教师允许",
     *  @SWG\Parameter(name="classroom_id",type="integer",in="path",description="班级"),
     *  @SWG\Parameter(name="course_id",type="integer",in="path",description="课程（非记录ID）"),
     *  @SWG\Response(response=204,description="ok"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function update(Classroom $classroom, $course)
    {
        $this->authorize('isClassroomTeacher', $classroom);

        /**
         * 获取课程、版本、章节、任务数据、任务结果数据
         *
         * 1. 迁移课程
         * 2. 迁移课程版本
         * 3. 迁移课程版本章节
         * 4. 迁移课程版本任务
         * 5. 迁移本班成员的学习记录
         * 6. 迁移题库
         * 7. 迁移考试、考试与题目的关联
         * 8. 迁移考试记录及题目记录
         * 9. 移除之前关联课程，添加新课程
         */

        $item = ClassroomCourse::where('classroom_id', $classroom->id)
            ->where('course_id', $course)->firstOrFail();

        if (!$item->is_synced) {
            $this->response->errorBadRequest(__('Classroom course has been canceled the synchronization.'));
        }
        $course = $item->course;
        $plan = $item->plan;

        Course::unsetEventDispatcher();
        Plan::unsetEventDispatcher();
        Task::unsetEventDispatcher();
        TaskResult::unsetEventDispatcher();
        \DB::transaction(function () use ($classroom, $course, $plan) {
            // 1
            $newCourse = $this->migrateCourse($course);
            $this->courseHash[$course->id] = $newCourse->id;

            // 2
            $newPlan = $this->migratePlan($plan);
            $this->planHash[$plan->id] = $newPlan->id;

            // 3
            $chapters = $plan->chapters()->where('parent_id', 0)->get();
            foreach ($chapters as $chapter) {
                $newChapter = $this->migrateChapter($chapter);
                $this->chapterHash[$chapter->id] = $newChapter->id;
                // 4
                foreach ($chapter->children as $section) {
                    $newSection = $this->migrateSection($section);
                    $this->chapterHash[$section->id] = $newSection->id;
                    // 5
                    foreach ($section->tasks as $task) {
                        $newTask = $this->migrateTask($task);
                        $this->taskHash[$task->id] = $newTask->id;
                        foreach ($task->results as $result) {
                            $newTaskResult = $this->migrateTaskResult($result);
                            $this->taskResultHash[$result->id] = $newTaskResult->id;
                        }
                    }
                }
            }

            // 6
            foreach ($course->questions as $question) {
                $newQuestion = $this->migrateQuestion($question);
                $this->questionHash[$question->id] = $newQuestion->id;
            }

            // 7
            foreach ($course->tests as $test) {
                $newTest = $this->migrateTest($test);
                $this->testHash[$test->id] = $newTest->id;
                // 8
                foreach ($test->results as $testResult) {
                    if (!empty($this->taskHash[$testResult->task_id])) {
                        $newTestResult = $this->migrateTestResult($testResult);
                        $this->testResultHash[$testResult->id] = $newTestResult->id;
                    }
                }
            }

            // 9
            ClassroomCourse::where('classroom_id', $classroom->id)
                ->where('course_id', $course->id)
                ->update([
                    'course_id' => $newCourse->id,
                    'plan_id' => $newPlan->id,
                    'is_synced' => false
                ]);
        });

        return $this->response->noContent();
    }

    /**
     * @SWG\Delete(
     *  path="/manage/classrooms/{classroom_id}/courses/{course_id}",
     *  tags={"web/classroom"},
     *  summary="删除",
     *  description="",
     *  @SWG\Parameter(name="classroom_id",type="integer",in="path",required=true,description="班级"),
     *  @SWG\Parameter(name="course_id",type="integer",in="path",required=true,description="课程"),
     *  @SWG\Response(response=204,description="ok"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function destroy(Classroom $classroom, $course)
    {
        $this->authorize('isClassroomTeacher', $classroom);

        /**
         * 如果是 copy 的，那么即删除其数据
         * 1. 删除课程
         * 2. 删除课程版本
         * 3. 删除课程版本章节
         * 4. 删除课程版本任务
         * 5. 删除本班成员的学习记录
         * 6. 删除题库
         * 7. 删除考试、考试与题目的关联
         * 8. 考试记录及题目记录
         * 9. 移除之前关联课程
         */
        $item = ClassroomCourse::where('classroom_id', $classroom->id)
            ->where('course_id', $course)
            ->firstOrFail();

        DB::transaction(function() use ($item) {
            if (!$item->is_synced) {
                // 移除所有copy的数据
                Course::where('id', $item->course_id)->forceDelete();
                Plan::where('course_id', $item->course_id)->forceDelete();
                Chapter::where('course_id', $item->course_id)->forceDelete();
                $taskIds = Task::where('course_id', $item->course_id)->pluck('id')->toArray();
                Task::where('course_id', $item->course_id)->forceDelete();
                TaskResult::where('course_id', $item->course_id)->forceDelete();
                Question::where('course_id', $item->course_id)->forceDelete();
                $testIds =  Test::where('course_id', $item->course_id)->pluck('id')->toArray();
                Test::where('course_id', $item->course_id)->forceDelete();
                TestQuestion::whereIn('test_id', $testIds)->forceDelete();
                TestResult::whereIn('task_id', $taskIds)->forceDelete();
                QuestionResult::whereIn('task_id', $taskIds)->forceDelete();
            }
            $item->delete();
        });

        return $this->response->noContent();
    }

    /**
     * 复制课程版本
     *
     * @param Plan $plan
     * @return \Illuminate\Database\Eloquent\Model
     */
    private function migratePlan(Plan $plan)
    {
        $newOne = $plan->replicate();
        $newOne->course_id = data_get($this->courseHash, $plan->course_id);
        $newOne->tasks_count = 0;
        $newOne->students_count = 0;
        $newOne->notes_count = 0;
        $newOne->reviews_count = 0;
        $newOne->topics_count = 0;
        $newOne->hit_count = 0;
        $newOne->rating = 0;
        $newOne->user_id = auth()->id();
        $newOne->copy_id = $plan->id;
        $newOne->save();

        return $newOne;
    }

    /**
     * 复制课程
     *
     * @param Course $course
     * @return \Illuminate\Database\Eloquent\Model
     */
    private function migrateCourse(Course $course)
    {
        $newOne = $course->replicate();

        $newOne->user_id = auth()->id();
        $newOne->copy_id = $course->id;
        $newOne->reviews_count = 0;
        $newOne->rating = 0;
        $newOne->notes_count = 0;
        $newOne->students_count = 0;
        $newOne->hit_count = 0;
        $newOne->materials_count = 0;

        $newOne->save();

        return $newOne;
    }

    /**
     * 复制章节、任务及任务结果
     *
     * @param Chapter $chapter
     * @return \Illuminate\Database\Eloquent\Model
     */
    private function migrateChapter(Chapter $chapter)
    {
        // 迁移章
        $newChapter = $chapter->replicate();
        $newChapter->course_id = data_get($this->courseHash, $chapter->course_id);
        $newChapter->plan_id = data_get($this->planHash, $chapter->plan_id);
        $newChapter->user_id = auth()->id();
        $newChapter->copy_id = $chapter->id;
        $newChapter->save();
        return $newChapter;
    }


    /**
     * @param Chapter $chapter
     * @return \Illuminate\Database\Eloquent\Model
     */
    private function migrateSection(Chapter $chapter)
    {
        // 迁移章
        $newChapter = $chapter->replicate();
        $newChapter->course_id = data_get($this->courseHash, $chapter->course_id);
        $newChapter->plan_id = data_get($this->planHash, $chapter->plan_id);
        $newChapter->user_id = auth()->id();
        $newChapter->parent_id = data_get($this->chapterHash, $chapter->parent_id);
        $newChapter->copy_id = $chapter->id;
        $newChapter->save();
        return $newChapter;
    }

    /**
     * @param Task $task
     * @return \Illuminate\Database\Eloquent\Model
     */
    private function migrateTask(Task $task)
    {
        $newTask = $task->replicate();
        $newTask->course_id = data_get($this->courseHash, $task->course_id);
        $newTask->plan_id = data_get($this->planHash, $task->plan_id);
        $newTask->chapter_id = data_get($this->chapterHash, $task->chapter_id);
        $newTask->user_id = auth()->id();
        $newTask->copy_id = $task->id;
        $newTask->save();

        return $newTask;
    }

    /**
     * 迁移任务结果
     *
     * @param TaskResult $result
     * @return \Illuminate\Database\Eloquent\Model
     */
    private function migrateTaskResult(TaskResult $result)
    {
        $newResult = $result->replicate();
        $newResult->course_id = data_get($this->courseHash, $result->course_id);
        $newResult->plan_id = data_get($this->planHash, $result->plan_id);
        $newResult->task_id = data_get($this->taskHash, $result->task_id);
        $newResult->user_id = auth()->id();
        $newResult->save();

        return $newResult;
    }

    /**
     * 迁移题目
     *
     * @param Question $question
     * @return \Illuminate\Database\Eloquent\Model
     */
    private function migrateQuestion(Question $question)
    {
        $newQuestion = $question->replicate();
        $newQuestion->user_id = auth()->id();
        $newQuestion->course_id = $question->course_id ? data_get($this->courseHash, $question->course_id) : null;
        $newQuestion->plan_id = $question->plan_id ? data_get($this->planHash, $question->plan_id) : null;
        $newQuestion->chapter_id = $question->chapter_id ? data_get($this->chapterHash, $question->chapter_id) : null;
        $newQuestion->user_id = auth()->id();
        $newQuestion->copy_id = $question->id;

        $newQuestion->save();

        return $newQuestion;
    }

    /**
     * 迁移考试
     *
     * @param Test $test
     * @return \Illuminate\Database\Eloquent\Model
     */
    private function migrateTest(Test $test)
    {
        $newTest = $test->replicate();
        $newTest->course_id = data_get($this->courseHash, $test->course_id);
        $newTest->user_id = auth()->id();
        $newTest->copy_id = $test->id;
        $newTest->save();

        return $newTest;
    }

    /**
     * 迁移考试记录及题目记录
     *
     * @param TestResult $result
     * @return \Illuminate\Database\Eloquent\Model
     */
    private function migrateTestResult(TestResult $result)
    {
        $newTestResult = $result->replicate();
        $newTestResult->task_id = data_get($this->taskHash, $result->task_id);
        $newTestResult->test_id = data_get($this->testHash, $result->test_id);
        $newTestResult->copy_id = $result->id;
        $newTestResult->save();

        // 考试与题目的关联关系
        $relationData = TestQuestion::where('test_id', $result->test_id)->get();
        foreach ($relationData as $testQuestion) {
            $newTestQuestion = $testQuestion->replicate();
            $newTestQuestion->question_id = data_get($this->questionHash, $testQuestion->question_id);
            $newTestQuestion->test_id = data_get($this->testHash, $testQuestion->test_id);
            $newTestQuestion->save();
        }

        return $newTestResult;
    }
}
