<?php

namespace App\Http\Requests\Web;

use App\Http\Requests\BaseRequest;

class ReviewRequest extends BaseRequest
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
                    'content' => 'required|string',
                    'rating' => 'required|numeric|min:0|max:5',
                ];
                break;
        }
    }
}
