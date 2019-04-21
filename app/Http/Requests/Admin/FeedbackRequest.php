<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class FeedbackRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'is_solved' => 'sometimes|bool',
            'is_replied' => 'sometimes|bool',
        ];
    }
}
