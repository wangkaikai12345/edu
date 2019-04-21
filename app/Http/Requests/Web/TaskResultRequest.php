<?php

namespace App\Http\Requests\Web;

use App\Http\Requests\BaseRequest;

class TaskResultRequest extends BaseRequest
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
                    'task_id' => 'required|Integer|min:0',
                    'watch_time' => 'required|Integer|min:0'
                ];
                break;
        }
    }
}
