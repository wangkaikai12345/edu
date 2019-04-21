<?php

namespace App\Http\Requests\Web;

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
            'applied_amount' => 'required|integer',
            'reason' => 'nullable|string|max:191',
        ];
    }
}
