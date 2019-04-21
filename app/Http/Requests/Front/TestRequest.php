<?php

namespace App\Http\Requests\Front;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class TestRequest extends BaseRequest
{
    /*
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'title' => 'required|max:191',
                ];
                break;
            case 'PUT':
                return [
                    'title' => 'required|max:191',
                ];
                break;
        }
    }
}
