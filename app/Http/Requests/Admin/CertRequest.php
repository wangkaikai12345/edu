<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class CertRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => 'required|in:cert_client,cert_key',
            'pem' => 'required|file',
        ];
    }
}
