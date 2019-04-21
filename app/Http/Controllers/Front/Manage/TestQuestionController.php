<?php

namespace App\Http\Controllers\Front\Manage;

use App\Enums\QuestionType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Front\TestQuestionRequest;
use App\Models\Question;
use App\Models\Test;
use DB;

class TestQuestionController extends Controller
{

    public function index(Test $test)
    {
        $this->authorize('isCourseTeacher', $test->course);

        $paginator = $test->questions()->filtered()->sorted()->paginate(self::perPage());

    }


    public function store(TestQuestionRequest $request, Test $test)
    {
        $this->authorize('isCourseTeacher', $test->course);

        // 题目获取
        $questions = $this->getQuestions();

        // 数据处理
        $totalScore = 0;
        $singleCount = 0;
        $multipleCount = 0;
        $judgeCount = 0;
        $temp = [];

        foreach ($questions as $question) {
            $temp[$question['id']] = ['score' => $request->score];
            $totalScore += $request->score;
            switch ($question->type) {
                case QuestionType::SINGLE:
                    $singleCount++;
                    break;
                case QuestionType::MULTIPLE:
                    $multipleCount++;
                    break;
                case QuestionType::JUDGE:
                    $judgeCount++;
                    break;
            }
        }

        DB::transaction(function () use ($request, $totalScore, $singleCount, $multipleCount, $judgeCount, $temp, $test) {
            // 同步题目
            $test->questions()->sync($temp);
            // 联动更新考试的总分数
            $test->total_score = $totalScore;
            $test->questions_count = count($temp);
            $test->single_count = $singleCount;
            $test->multiple_count = $multipleCount;
            $test->judge_count = $judgeCount;
            $test->save();
        });

    }


    public function update(TestQuestionRequest $request, Test $test)
    {
        $this->authorize('isCourseTeacher', $test->course);

        // 数据处理
        $totalScore = 0;
        $singleCount = 0;
        $multipleCount = 0;
        $judgeCount = 0;
        $temp = [];
        foreach ($request->questions as $question) {
            $temp[$question['id']] = ['score' => $question['score']];
            $totalScore += $question['score'];
            $type = Question::where('id', $question['id'])->value('type');
            switch ($type) {
                case QuestionType::SINGLE:
                    $singleCount++;
                    break;
                case QuestionType::MULTIPLE:
                    $multipleCount++;
                    break;
                case QuestionType::JUDGE:
                    $judgeCount++;
                    break;
            }
        }

        DB::transaction(function () use ($request, $totalScore, $singleCount, $multipleCount, $judgeCount, $temp, $test) {
            // 同步题目
            $test->questions()->sync($temp);
            // 联动更新考试的总分数
            $test->total_score = $totalScore;
            $test->questions_count = count($request->questions);
            $test->single_count = $singleCount;
            $test->multiple_count = $multipleCount;
            $test->judge_count = $judgeCount;
            $test->save();
        });

    }

    /**
     * 根据题目难度比例、类型、题库源获取一定数量的题目
     *
     * 1. 判断难度系数 1-5 难度系数的占比
     * 2. 判断实际题目数量是否与需求题目数是否能够满足
     * 3. 根据难度占比计算题目数量 computedQuestionScale（其总数不能超过 questions_count 参数）
     * 4. 根据题目个数，获取对应的题目。
     *
     * @return \Illuminate\Support\Collection
     */
    private function getQuestions()
    {
        $difficulty = request('difficulty', [20, 20, 20, 20, 20]);
        if (array_sum($difficulty) !== 100) {
            $this->response->errorBadRequest(__('The sum of difficulty is equal to 100.'));
        }

        $questionsCount = request('questions_count');

        $count = Question::whereIn('type', request('type'))
            ->where(request('source_type'), request('source_id'))
            ->count();
        if ($questionsCount > $count) {
            $this->response->errorBadRequest(__('The actual quantity is greater than the quantity required.'));
        }

        $computedQuestionsScale = [];
        foreach ($difficulty as $index => $scale) {
            // 是否超出题目个数
            if (array_sum($computedQuestionsScale) >= $questionsCount) {
                continue;
            }
            // 当为最后一个时，取既定个数与已有个数的差
            if ($index === 4) {
                $computedQuestionsScale[] = $questionsCount - array_sum($computedQuestionsScale);
            } else {
                $computedQuestionsScale[] = $scale ? (int)round($questionsCount * $scale / 100) : 0;
            }
        }

        // 获取题目
        $questions = collect();
        $index = 0;
        while ($questions->count() < $questionsCount) {
            // 个数限制（只有当索引超出4时，才会出现不存在的状况）
            $limit = isset($computedQuestionsScale[$index]) ? $computedQuestionsScale[$index] : $questionsCount - $questions->count();

            // 难度系数，难度系数大于 5 时，随机出现难度系数
            $coefficient = $index + 1 <= 5 ? $index + 1 : array_random([1, 2, 3, 4, 5]);
            $items = Question::whereIn('type', request('type'))
                ->where(request('source_type'), request('source_id'))
                ->where('difficulty', $coefficient)
                ->limit($limit)
                ->whereNotIn('id', $questions->pluck('id')->toArray())
                ->get(['id', 'type']);

            // $questions = $questions->merge($items); // 该行代码不生效
            $items->each(function ($item) use ($questions) {
                $questions->push($item);
            });
            $index++;
        }

        return $questions;
    }
}
