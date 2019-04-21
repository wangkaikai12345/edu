<?php

namespace App\Http\Requests\Backstage;

use App\Http\Requests\BaseRequest;

class NotificationRequest extends BaseRequest
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
                    'user_ids' => 'sometimes|array',
                    'all' => 'sometimes|boolean'
                ];
                break;
        }
    }
}
