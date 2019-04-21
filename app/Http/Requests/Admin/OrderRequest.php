<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class OrderRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'PUT':
                return [
                    'pay_amount' => 'required|integer|min:0',
                ];
                break;
        }
    }
}
