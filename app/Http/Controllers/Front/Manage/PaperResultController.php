<?php

namespace App\Http\Controllers\Front\Manage;

use App\Models\ClassroomCourse;
use App\Models\ClassroomTeacher;
use App\Models\Paper;
use App\Models\PaperQuestion;
use App\Models\PaperResult;
use App\Models\PlanTeacher;
use App\Models\Question;
use App\Models\QuestionResult;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class PaperResultController extends Controller
{
    /**
     * 待阅卷列表
     */
    public function index(Request $request, PaperResult $paperResult)
    {
        $uid = auth('web')->id();
        $title = $request->title;
        $username = $request->username;

        // 查询用户所有能管理的版本
        $planIds_course = PlanTeacher::where('user_id', $uid)->pluck('plan_id')->toArray();

        $classroomIds = ClassroomTeacher::where('user_id', $uid)->pluck('classroom_id');

        $planIds_class = ClassroomCourse::whereIn('classroom_id', $classroomIds)->pluck('plan_id')->toArray();

        $plan_ids = array_merge($planIds_course, $planIds_class);

        $paperResults = $paperResult->when($title, function ($query) use ($title) {
            return $query->where('paper_title', 'like', '%' . $title . '%');
        })->when($username, function ($query) use ($username) {
            $uids = User::where('username', 'like', '%' . $username . '%')->pluck('id');
            return $query->whereIn('user_id', $uids);
        })
            ->where('mark_type', 'teacher')
            ->whereIn('plan_id', $plan_ids)
            ->orderByDesc('id')
            ->with(['paper', 'user', 'task'])
            ->paginate(config('theme.teacher_num'));
        return view('teacher.exam.paper_mark_list', compact('paperResults'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存阅卷
     */
    public function store(Request $request, Paper $paper, PaperResult $paperResult)
    {
        try {
            DB::beginTransaction();
            // 提交的数据
            $read_answer_data = collect($request->read_answer_data)->keyBy('answer_id');

            // 查询试卷所有提交的答案
            $answerResults = $paperResult->results;

            // 循环处理
            $allScore = 0;
            foreach ($answerResults as $answerResult) {
                $data = isset($read_answer_data[$answerResult->id]) ? $read_answer_data[$answerResult->id] : null;

                if (!empty($data)) {
                    $answerResult->score = $data['score'];
                    $answerResult->explain = $data['remark'];
                    $allScore += $data['score'];
                }

                $answerResult->status = 3;
                $answerResult->save();
            }

            // 修改主表状态
            $paperResult->answer_score = $paperResult->answer_score + $allScore;
            $paperResult->reader_id = auth('web')->id();
            $paperResult->is_mark = 1;
            $paperResult->save();

            DB::commit();
            return ajax('200', '阅卷完成');
        } catch (\Exception $e) {
            DB::rollBack();
            return ajax('400', '错误', ['msg' => $e]);
        }

    }

    /**
     * 一个阅卷的详情
     */
    public function show($id)
    {
        // 学员答题情况-主记录表
        $paperResult = PaperResult::find($id);

        // 试卷信息
        $paper = $paperResult->paper;

        // 试卷与题目关联信息
        $paperQuestions = $paper->paperQuestions->keyBy('question_id');

        // 试卷下所有题目id
        $qids = $paperQuestions->pluck('question_id');

        // 所有题目
        $questions = Question::whereIn('id', $qids)->get()->keyBy('id');

        // 学员答题的信息
        $questionResults = QuestionResult::where('paper_result_id', $id)->get()->keyBy('question_id');

        // 第一题
        $firstQuestion = $questionResults->first()->question;

        return view('teacher.exam.paper_mark', compact(
            'paper',
            'paperResult',
            'questions',
            'questionResults',
            'firstQuestion',
            'paperQuestions'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
