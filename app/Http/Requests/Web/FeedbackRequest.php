<?php

namespace App\Http\Requests\Web;

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
            'content' => 'required|string',
            'email' => 'nullable|string',
            'wechat' => 'nullable|string',
            'qq' => 'nullable|string',
        ];
    }
}
