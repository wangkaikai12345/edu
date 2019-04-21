<?php

namespace App\Http\Requests\Front;

use App\Http\Requests\BaseRequest;
use App\Rules\TestQuestionRule;

class TestQuestionRequest extends BaseRequest
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
                    'source_type' => 'required|in:course_id,plan_id,chapter_id,task_id',
                    'source_id' => 'required|integer',
                    'questions_count' => 'required|integer',
                    'type' => ['required', 'distinct', new TestQuestionRule()],
                    'score' => 'required|integer|min:1|max:100',
                    'difficulty' => ['nullable', 'array', 'size:5'],
                ];
                break;
            case 'PATCH':
            case 'PUT':
                return [
                    'questions' => ['required', 'distinct', new TestQuestionRule()],
                ];
                break;
        }

    }
}
