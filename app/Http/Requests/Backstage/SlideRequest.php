<?php

namespace App\Http\Requests\Backstage;

use App\Http\Requests\BaseRequest;
use App\Rules\SlideSortRule;

class SlideRequest extends BaseRequest
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
                    'title' => 'required|string|min:1',
                    'image' => ['required'],
                    'link' => 'required|string|url',
                    'description' => 'nullable|max:191',
                ];
                break;
            case 'PUT':
                return [
                    'title' => 'sometimes|string|min:1',
                    'image' => ['sometimes'],
                    'link' => 'sometimes|string|url',
                    'description' => 'sometimes|max:191',
                ];
                break;
            case 'PATCH':
                return [
                    'ids' => ['required', 'array', 'distinct', new SlideSortRule()]
                ];
                break;
        }
    }
}
