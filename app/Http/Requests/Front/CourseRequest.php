<?php

namespace App\Http\Requests\Front;

use App\Enums\SerializeMode;
use App\Enums\Status;
use App\Http\Requests\BaseRequest;
use App\Rules\CustomEnumRule;
use App\Rules\TagsRule;
use Illuminate\Validation\Rule;

class CourseRequest extends BaseRequest
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
                    'title' => ['required', 'string', 'max:30', Rule::unique('courses')->whereNull('deleted_at')]
                ];
                break;
            case 'PATCH':
                return ['status' => ['required', 'in:published,closed']];
                break;
            case 'PUT':
                return [
                    'title' => ['sometimes', 'string', 'max:30', Rule::unique('courses')->whereNull('deleted_at')->ignore($this->course->id)],
//                    'subtitle' => 'nullable|string|max:50',
//                    'summary' => 'nullable|string',
//                    'category_id' => 'nullable|exists:categories,id',
//                    'goals' => 'nullable|array',
//                    'audiences' => 'nullable|array',
//                    'cover' => 'nullable|string|max:191',
//                    'serialize_mode' => ['sometimes', new CustomEnumRule(SerializeMode::class)],
//                    'tags' => ['nullable', 'array', new TagsRule()]
                ];
                break;
        }
    }
}