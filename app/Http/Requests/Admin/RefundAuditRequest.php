<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class RefundAuditRequest extends BaseRequest
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
                    'agree' => 'required|boolean',
                    'handled_reason' => 'required_if:agree,0|min:1',
                ];
                break;
        }
    }

    public function messages()
    {
        return [
            'handled_reason.required_if' => '当不同意退款 必须填写 原因。',
        ];
    }
}
