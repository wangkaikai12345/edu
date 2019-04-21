<?php

namespace App\Http\Requests\Front;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class FollowRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'follow_id' => ['required', Rule::exists('users', 'id'),],
        ];
    }
}
