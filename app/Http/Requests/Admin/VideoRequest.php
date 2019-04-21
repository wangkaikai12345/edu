<?php

namespace App\Http\Requests\Admin\Admin;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class VideoRequest extends BaseRequest
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
                    'type' => 'required|in:video',
                    'media_uri' => 'required|url',
                ];
                break;
        }
    }
}
