<?php

namespace App\Http\Requests\Web;

use App\Enums\TopicType;
use App\Http\Requests\BaseRequest;
use App\Rules\CustomEnumRule;

class TopicRequest extends BaseRequest
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
                    'type' => ['required', new CustomEnumRule(TopicType::class)],
                    'title' => 'required|string|max:191',
                    'content' => 'required|string',
                    'task_id' => 'nullable|integer|exists:tasks,id'
                ];
                break;
        }
    }
}
