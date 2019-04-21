<?php

namespace App\Http\Requests\Front;

use App\Http\Requests\BaseRequest;

class RefundRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'reason' => 'required|string|max:191',
        ];
    }
}
