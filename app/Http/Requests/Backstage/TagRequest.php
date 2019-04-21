<?php

namespace App\Http\Requests\Backstage;

use App\Http\Requests\BaseRequest;
use App\Rules\TagRule;
use Illuminate\Validation\Rule;

class TagRequest extends BaseRequest
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
                    'name' => [
                        'required',
                        'string',
                        'max:30',
                        Rule::unique('tags', 'name')
                            ->where(function ($q) {
                                return $q->where('tag_group_id', $this->route('tagGroup')->id)
                                    ->where('deleted_at', null);
                            })

                    ]
                ];
                break;
            case 'PUT':
                return [
                    'name' => [
                        'required',
                        'string',
                        'max:30',
                        Rule::unique('tags')
                            ->where(function ($q) {
                                return $q->where('tag_group_id', $this->route('tagGroup')->id)
                                    ->where('deleted_at', null);
                            })
                            ->ignore($this->route('tag'))
                    ],
                ];
                break;
            case 'DELETE':
                return [
                    'ids' => ['required', 'array', 'bail', new TagRule($this->tagGroup)]
                ];
                break;
        }
    }
}
