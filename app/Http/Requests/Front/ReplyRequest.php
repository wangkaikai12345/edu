<?php

namespace App\Http\Requests\Front;

use App\Http\Requests\BaseRequest;

class ReplyRequest extends BaseRequest
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
                    'content' => 'required|string|min:1|max:1000',
                ];
                break;
        }
    }
}
