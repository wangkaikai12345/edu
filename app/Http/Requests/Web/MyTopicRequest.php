<?php

namespace App\Http\Requests\Web;

use App\Enums\TopicType;
use App\Http\Requests\BaseRequest;
use App\Rules\CustomEnumRule;

class MyTopicRequest extends BaseRequest
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
                    'plan_id' => 'required|exists:plan,id',
                    'type' => ['required', new CustomEnumRule(TopicType::class)],
                    'title' => 'required|string|max:255',
                    'content' => 'required|string',
                    'task_id' => 'sometimes'
                ];
                break;
            case 'PUT':
                return [
                    'title' => 'required|string|max:191',
                    'content' => 'required|string',
                ];
                break;
        }
    }
}
