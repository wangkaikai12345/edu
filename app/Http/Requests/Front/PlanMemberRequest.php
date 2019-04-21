<?php

namespace App\Http\Requests\Front;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class PlanMemberRequest extends BaseRequest
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
//                    'user_id' => [
//                        'required',
//                        'exists:users,id',
//                        Rule::unique('plan_members')->where('plan_id', $this->plan->id)
//                    ],
                    'status' => 'nullable|string',
                    'remark' => 'nullable|max:100'
                ];
                break;
            case 'PUT':
                return [
                    'status' => 'nullable|string',
                    'remark' => 'nullable|max:100'
                ];
                break;
        }
    }
}
