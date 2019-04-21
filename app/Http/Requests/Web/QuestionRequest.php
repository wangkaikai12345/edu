<?php

namespace App\Http\Requests\Web;

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
                    'title' => 'required|max:191',
                    'options' => 'required|array|distinct',
                    'type' => ['required', new CustomEnumRule(QuestionType::class)],
                    'answers' => 'required|distinct|array',
                    'plan_id' => [
                        'nullable',
                        Rule::exists('plans', 'id')->where('course_id', $this->course->id)
                    ],
                    'chapter_id' => [
                        'nullable',
                        Rule::exists('chapters', 'id')->where('plan_id', $this->plan_id)
                    ],
                    'difficulty' => 'required|integer|min:1|max:5',
                    'explain' => 'nullable|string|max:191',
                ];
                break;
            case 'PUT':
                return [
                    'title' => 'required|max:191',
                    'options' => 'required|array|distinct',
                    'type' => ['required', new CustomEnumRule(QuestionType::class)],
                    'answers' => 'required|distinct|array',
                    'plan_id' => [
                        'nullable',
                        Rule::exists('plans', 'id')->where('course_id', $this->course->id)
                    ],
                    'chapter_id' => [
                        'nullable',
                        Rule::exists('chapters', 'id')->where('course_id', $this->course->id)
                    ],
                    'difficulty' => 'required|integer|min:1|max:5',
                    'explain' => 'nullable|string|max:191',
                ];
                break;
        }
    }
}
