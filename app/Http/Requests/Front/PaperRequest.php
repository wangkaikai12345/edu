<?php

namespace App\Http\Requests\Front;

use App\Enums\PaperStatus;
use App\Http\Requests\BaseRequest;
use App\Rules\CustomEnumRule;

class PaperRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'title' => 'required|max:191',
                    'tags' => 'required|max:191',
                    'expect_time' => 'required|integer',
                    'total_score' => 'required|integer|min:1',
                    'pass_score' => 'required|integer|min:1|max:total_score',
                    'question_ids' => 'required|array|distinct',
                ];
                break;
            case 'PUT':
                return [
                    'title' => 'required|max:191',
                    'status' => ['nullable', new CustomEnumRule(PaperStatus::class)],
                ];
                break;
        }
    }

    public function messages()
    {
        return [
            'question_ids.required' => '创建试卷必须要添加题目!',
            'total_score.required' => '总分不能为空!',
            'total_score.integer' => '总分必须是整数!',
            'pass_score.required' => '及格分数不能为空!',
            'pass_score.integer' => '及格分数必须是整数!',
        ];
    }
}
