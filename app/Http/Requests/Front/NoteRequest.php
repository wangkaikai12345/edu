<?php

namespace App\Http\Requests\Front;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class NoteRequest extends BaseRequest
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
                    'is_public' => 'sometimes|boolean',
                ];

                break;
        }
    }
}
