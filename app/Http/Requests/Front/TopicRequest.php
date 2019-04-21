<?php

namespace App\Http\Requests\Front;

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
//                    'type' => ['required', new CustomEnumRule(TopicType::class)],
                    'type' => ['required'],
                    'title' => 'required|string|max:191',
                    'content' => 'required|string',
                ];
                break;
        }
    }
}
