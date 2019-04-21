<?php

namespace App\Http\Requests\Front;

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
                'model_type' => ['required','in:course,topic,note'],
                'model_id' => ['required', 'integer', 'exists:' . str_plural($this->model_type) . ',id'],
            ];
        }

        return [
            'model_type' => ['required', 'in:course,topic,note'],
            'model_id' => ['required', 'integer'],
        ];
    }
}
