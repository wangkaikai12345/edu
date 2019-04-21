<?php

namespace App\Http\Controllers\Front\Manage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\QuestionRequest;
use App\Models\ModelHasTag;
use App\Models\Question;
use App\Models\Tag;
use App\Models\TagGroup;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    /**
     * 获取问题数据
     */
    private function getQuestionDatas (Request $request, Question $question, $type = 'all')
    {
        $title = $request->title;
        $username = $request->username;
        $tag = $request->tag;
        $questions = $question
            ->where(function ($query) use ($username) {
                if (!empty($username)) {
                    $user = User::where('username', $username)->first();
                    $uid = !empty($user) ? $user->id : null;
                    return $query ->where('user_id', $uid);
                }
            })
            ->when($type == 'select', function ($query) {
                return $query->whereIn('type', ['single', 'multiple']);
            })
            ->when($title, function ($query) use ($title) {
                return $query->where('title', 'like', '%' . $title . '%');
            })
            ->when($tag, function ($query) use ($tag) {
                $tids = Tag::where('name', 'like', "%{$tag}%")->pluck('id');
                $mids = ModelHasTag::where('model_type', 'question')
                    ->whereIn('tag_id', $tids)
                    ->pluck('model_id');
                return $query->whereIn('id', $mids);
            })
            ->with(['user' => function ($query) {
                $query->select('id', 'username');
            }, 'tags' => function ($query) {
                $query->select('id', 'name');
            }])
            ->orderBy('id', 'DESC')
            ->paginate(config('theme.teacher_num'));
        return $questions;
    }

    /**
     * 问题列表
     */
    public function index(Request $request, Question $question)
    {
        $questions = $this->getQuestionDatas($request, $question);
//        $labels = $this->labels();
        return view('teacher.exam.index', compact('questions'));
    }

    /**
     * 创建问题页面
     */
    public function create()
    {
        $labels = $this->labels();
        return view('teacher.exam.insert_subject', compact('labels'));
    }

    /**
     * 添加问题
     */
    public function store(QuestionRequest $request, Question $question)
    {
        try{
            DB::beginTransaction();
            $question->fill(array_only($request->all(), ['type', 'title', 'answers', 'explain', 'options']));
            $question->user_id = auth('web')->id() ?? 1;
            $question->rate = $request->score;
            $question->save();

            // 如果有标签, 添加标签关联
            $tags = $request->input('tags');
            $question->tags()->sync($tags);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return ajax('400', '添加题目失败!');
        }
        return ajax('200', '添加题目成功!');
    }

    /**
     * 查看一个问题
     */
    public function show(Question $question)
    {
        return view('teacher.exam.subject_preview', compact('question'));
    }

    /**
     * 编辑问题页面
     *
     * @param Question $question
     * @author Luwnto
     */
    public function edit(Question $question)
    {
        dd($question, '编辑问题页面');
    }

    /**
     * 更新问题
     *
     * @param QuestionRequest $request
     * @param Question $question
     * @author Luwnto
     */
    public function update(QuestionRequest $request, Question $question)
    {
        $question->fill(array_only($request->all(), ['title', 'options', 'type', 'answers', 'rate', 'explain', 'status']));
        $question->save();
        dd('问题更新成功');
    }

    /**
     * 通过ajax返回需要的题目
     */
    public function questionJson(Request $request, Question $question)
    {
        $questions = $this->getQuestionDatas($request, $question);
//        $labels = $this->labels();
        return view('teacher.exam.add-question-list', compact('questions'));
    }

    /**
     * 通过ajax返回需要的题目
     */
    public function questionVideo(Request $request, Question $question)
    {
        $questions = $this->getQuestionDatas($request, $question, 'select');
        return view('teacher.plan.modal.choice-question', compact('questions'));
    }

    /**
     * 生成需要的标签数据
     */
    private function labels()
    {
        $labels = Tag::select('id', 'name as text')
            ->where(function ($q){
                return $q->where('tag_group_id', TagGroup::where('name', 'course')->first()->id);
            })->get();
        return $labels;
    }


//    public function index(Course $course)
//    {
//        $this->authorize('isCourseTeacher', $course);
//
//        $paginate = $course->questions()->filtered()->sorted()->paginate(self::perPage());
//
//    }
//
//    public function store(QuestionRequest $request, Course $course, Question $question)
//    {
//        $this->authorize('isCourseTeacher', $course);
//
//        $question->fill($request->all());
//        $question->course_id = $course->id;
//        $question->user_id = auth('web')->id();
//        $question->save();
//
//    }
//
//    public function update(QuestionRequest $request, Course $course, $question)
//    {
//        $question = $course->questions()->findOrFail($question);
//
//        $this->authorize('isCourseTeacher', $course);
//
//        $question->fill(array_only($request->all(), ['title', 'options', 'type', 'answers', 'plan_id', 'chapter_id', 'difficulty', 'explain']));
//        $question->save();
//
//    }
//
//    public function destroy(Course $course, $question)
//    {
//        $question = $course->questions()->findOrFail($question);
//
//        $this->authorize('isCourseTeacher', $course);
//
//        $question->delete();
//
//    }
}
