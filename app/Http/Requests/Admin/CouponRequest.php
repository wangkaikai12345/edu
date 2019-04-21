<?php

namespace App\Http\Requests\Admin;

use App\Enums\CouponType;
use App\Enums\ProductType;
use App\Http\Requests\BaseRequest;
use App\Rules\CouponRule;
use App\Rules\CustomEnumRule;

class CouponRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->type) {
            return [
                'type' => ['required', new CustomEnumRule(CouponType::class)],
                'value' => ['required', new CouponRule($this->type)],
                'expired_at' => ['required', 'date', 'after:now'],
                'consumer_id' => ['nullable', 'exists:users,id'],
                'product_type' => ['nullable', new CustomEnumRule(ProductType::class)],
                'remark' => ['nullable', 'max:100'],
                'number' => ['nullable', 'max:1000']
            ];
        }
        return [
            'type' => ['required', new CustomEnumRule(CouponType::class)],
            'value' => ['required'],
            'expired_at' => ['required', 'date', 'after:now'],
            'consumer_id' => ['nullable', 'exists:users,id'],
            'product_type' => ['nullable', new CustomEnumRule(ProductType::class)],
            'remark' => ['nullable', 'max:100'],
            'number' => ['nullable', 'max:1000']
        ];
    }
}
