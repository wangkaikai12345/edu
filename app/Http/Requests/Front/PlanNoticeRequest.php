<?php

namespace App\Http\Requests\Front;

use App\Http\Requests\BaseRequest;

class PlanNoticeRequest extends BaseRequest
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
                    'content' => 'required|string',
                    'started_at' => 'required|date',
                    'ended_at' => 'required|date|after:started_at',
                ];
                break;
            case 'PUT':
                return [
                    'content' => 'required|string',
                    'started_at' => 'required|date',
                    'ended_at' => 'required|date|after:started_at',
                ];
                break;
        }
    }

}
