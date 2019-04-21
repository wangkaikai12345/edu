<?php

namespace App\Http\Requests\Backstage;

use App\Http\Requests\BaseRequest;
use App\Rules\CategoryRule;
use Illuminate\Validation\Rule;

class NavigationRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            // 添加
            case 'POST':
                if (empty($this->route('navigation'))) {
                    return [
                        'name' => ['required', 'string', 'max:100', Rule::unique('navigations', 'name')->where('type', $this->get('type'))],
                        'link' => ['required', 'max:150', 'url'],
                        'target' => ['required', 'boolean'],
                        'status' => ['required', 'boolean'],
                    ];
                } else {
                    return [
                        'name' => [
                            'required',
                            'string',
                            'max:100',
                            Rule::unique('navigations', 'name')
                                ->where('parent_id', $this->route('navigation')->id)
                                ->where('type', $this->get('type'))
                        ],
                        'link' => ['required', 'max:150', 'url'],
                        'target' => ['required', 'boolean'],
                        'status' => ['required', 'boolean'],
                    ];
                }
                break;
            // 添加
            case 'PUT':
                $navigation = $this->route('navigation');


                if (empty($navigation->parent_id)) {
                    return [
                        'name' => ['required', 'string', 'max:100', Rule::unique('navigations', 'name')->ignore($navigation->id)
                            ->where(function ($query) use ($navigation) {
                                $query->where('type', $navigation->type)->where('deleted_at', null);
                            })],
                        'link' => ['required', 'max:150', 'url'],
                        'target' => ['required', 'boolean'],
                        'status' => ['required', 'boolean'],
                    ];
                } else {
                    return [
                        'name' => ['required', 'string', 'max:100', Rule::unique('navigations', 'name')->ignore($navigation->id)
                            ->where(function ($query) use ($navigation) {
                                $query->where('type', $navigation->type)->where('deleted_at', null)->where('parent_id', $navigation->parent_id);
                            })],
                        'link' => ['required', 'max:150', 'url'],
                        'target' => ['required', 'boolean'],
                        'status' => ['required', 'boolean'],
                    ];
                }

                break;
        }
    }
}
