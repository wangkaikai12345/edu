<?php

namespace App\Http\Requests\Front;

use App\Enums\TaskType;
use App\Http\Requests\BaseRequest;
use App\Rules\CustomEnumRule;

class FrontTaskRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
//            'type' => 'sometimes', new CustomEnumRule(TaskType::class),
            'task' => 'sometimes|exists:tasks,id',
            'cid' => 'sometimes|exists:classrooms,id',
        ];
    }

}
