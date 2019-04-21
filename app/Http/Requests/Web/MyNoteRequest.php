<?php

namespace App\Http\Requests\Web;

use App\Http\Requests\BaseRequest;

class MyNoteRequest extends BaseRequest
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
                    'content' => 'string',
                    'status' => 'in:private,public'
                ];
                break;
        }
    }
}
