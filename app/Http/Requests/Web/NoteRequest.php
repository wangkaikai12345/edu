<?php

namespace App\Http\Requests\Web;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class NoteRequest extends BaseRequest
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
                    'task_id' => [
                        'required',
                        'integer',
                        Rule::exists('tasks', 'id')->where('plan_id', $this->plan->id)->whereNull('deleted_at')
                    ],
                    'content' => 'required|string',
                ];

                break;
        }
    }
}
