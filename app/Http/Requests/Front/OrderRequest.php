<?php

namespace App\Http\Requests\Front;

use App\Enums\Currency;
use App\Enums\ProductType;
use App\Http\Requests\BaseRequest;
use App\Rules\CustomEnumRule;

class OrderRequest extends BaseRequest
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
                if ($this->product_type) {
                    return [
//                        'product_type' => ['required', new CustomEnumRule(ProductType::class)],
                        'product_type' => ['required'],
                        'product_id' => ['required', 'integer', 'exists:' . str_plural($this->product_type) . ',id'],
                        'coupon_code' => 'sometimes|string|exists:coupons,code',
//                        'currency' => ['sometimes', 'string', new CustomEnumRule(Currency::class)]
                        'currency' => ['sometimes', 'string']
                    ];
                }
                return [
//                    'product_type' => ['required', new CustomEnumRule(ProductType::class)],
                    'product_type' => ['required'],
                    'product_id' => ['required', 'integer'],
                    'coupon_code' => 'sometimes|string|exists:coupons,code',
//                    'currency' => ['sometimes', 'string', new CustomEnumRule(Currency::class)]
                    'currency' => ['sometimes', 'string']
                ];
                break;
            case 'PATCH':
            case 'PUT':
                return [
                    'closed_message' => 'nullable|string|max:255'
                ];
                break;
        }
    }

}
