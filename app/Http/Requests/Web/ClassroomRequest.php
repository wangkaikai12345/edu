<?php

namespace App\Http\Requests\Web;

use App\Enums\ExpiryMode;
use App\Http\Requests\BaseRequest;
use App\Rules\CustomEnumRule;

class ClassroomRequest extends BaseRequest
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
                    'title' => 'required|string|max:32',
                    'description' => 'nullable|string',
                    'expiry_mode' => ['required', new CustomEnumRule(ExpiryMode::class)],
                    'expiry_started_at' => 'nullable|date',
                    'ended_at' => 'nullabl|date',
                    'expiry_days' => 'sometimes|integer|min:0',
                    'category_id' => 'nullable|integer|exists:categories,id',
                    'price' => 'sometimes|integer|min:0',
                    'origin_price' => 'sometimes|integer|min:0',
                    'cover' => 'nullable|string',
                ];
            case 'PUT':
                return [
                    'title' => 'sometimes|string|max:32',
                    'description' => 'nullable|string',
                    'expiry_mode' => ['sometimes', new CustomEnumRule(ExpiryMode::class)],
                    'expiry_started_at' => 'nullable|date',
                    'ended_at' => 'nullable|date',
                    'expiry_days' => 'sometimes|integer|min:0',
                    'category_id' => 'nullable|integer|exists:categories,id',
                    'price' => 'sometimes|integer|min:0',
                    'origin_price' => 'sometimes|integer|min:0',
                    'cover' => 'nullable|string',
                ];
                break;
        }
    }
}
