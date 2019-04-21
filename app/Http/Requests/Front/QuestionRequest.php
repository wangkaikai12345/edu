<?php

namespace App\Http\Requests\Front;

use App\Enums\QuestionType;
use App\Http\Requests\BaseRequest;
use App\Rules\CustomEnumRule;
use Illuminate\Validation\Rule;

class QuestionRequest extends BaseRequest
{
    /*
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'title' => 'required|string',
                    'tags' => 'required|max:191',
//                    'type' => ['required', new CustomEnumRule(QuestionType::class)],
                    'type' => ['required'],
                    'options' => 'required_if:type,single,multiple|nullable|array|distinct',
                    'score' => 'required|integer|min:1|max:5',
                    'answers' => 'required_if:type,single,multiple|nullable|distinct|array',
                    'explain' => 'nullable|string',
                ];
                break;
            case 'PUT':
                return [
                    'title' => 'required|max:191',
                    'type' => ['required'],
//                    'type' => ['required', new CustomEnumRule(QuestionType::class)],
                    'options' => 'nullable|array|distinct',
                    'rate' => 'required|integer|min:1|max:5',
                    'answers' => 'nullable|distinct|array',
                    'explain' => 'nullable|string|max:191',
                ];
                break;
        }
    }

    public function messages()
    {
        return [
            'score.required' => '题目难度不能为空',
            'options.required_if' => '题目类型为单选或者多选, 必须添加题目选项!',
            'answers.required_if' => '题目类型为单选或者多选, 必须添加正确答案!',
        ];
    }

}
