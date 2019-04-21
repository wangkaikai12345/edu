<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class RechargingRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|max:32',
            'price' => 'required|integer|min:1',
            'origin_price' => 'required|integer|min:1',
            'coin' => 'required|integer|min:1',
            'extra_coin' => 'required|integer|min:0',
        ];
    }
}
