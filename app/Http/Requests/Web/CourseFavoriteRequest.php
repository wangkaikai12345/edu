<?php

namespace App\Http\Requests\Web;

use App\Enums\FavoriteType;
use App\Http\Requests\BaseRequest;
use App\Rules\CustomEnumRule;

class FavoriteRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->model_type) {
            return [
                'model_type' => ['required', new CustomEnumRule(FavoriteType::class)],
                'model_id' => ['required', 'integer', 'exists:' . str_plural($this->model_type) . ',id'],
            ];
        }

        return [
            'model_type' => ['required', new CustomEnumRule(FavoriteType::class)],
            'model_id' => ['required', 'integer'],
        ];
    }
}
