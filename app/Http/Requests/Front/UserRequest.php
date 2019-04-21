<?php

namespace App\Http\Requests\Front;

use App\Enums\Gender;
use App\Http\Requests\BaseRequest;
use App\Rules\CustomEnumRule;

class UserRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {

            case 'PUT':
                return [
                    'avatar'                   => 'sometimes|max:191',
                    'tags'                     => 'sometimes|nullable|array',
                    'signature'                => 'sometimes|nullable|string|max:191',
                    'profile.name'             => 'sometimes|nullable|string',
//                    'profile.gender'           => ['sometimes', new CustomEnumRule(Gender::class)],
                    'profile.gender'           => ['sometimes'],
                    'profile.title'            => 'sometimes|nullable|string',
                    'profile.about'            => 'sometimes|nullable|string',
                    'profile.company'          => 'sometimes|nullable|string',
                    'profile.job'              => 'sometimes|nullable|string',
                    'profile.site'             => 'sometimes|nullable|string',
                    'profile.weibo'            => 'sometimes|nullable|string',
                    'profile.weixin'           => 'sometimes|nullable|string',
                    'profile.qq'               => 'sometimes|nullable|string',
                    'profile.school'           => 'sometimes|nullable|string',
                    'profile.major'            => 'sometimes|nullable|string',
                    'profile.city'             => 'sometimes|nullable|string',
                    'profile.birthday'         => 'sometimes|nullable|date',
                    'profile.idcard'           => 'sometimes|nullable|string',
                    'profile.is_qq_public'     => 'sometimes|boolean',
                    'profile.is_weixin_public' => 'sometimes|boolean',
                    'profile.is_weibo_public'  => 'sometimes|boolean',
                ];
                break;
        }
    }

    public function attributes()
    {
        return [
            'profile.name'             => '真实姓名',
            'profile.gender'           => '性别',
            'profile.title'            => '头衔',
            'profile.signature'        => '签名',
            'profile.about'            => '个人介绍',
            'profile.company'          => '公司',
            'profile.job'              => '工作',
            'profile.site'             => '个人网站',
            'profile.weibo'            => '微博',
            'profile.weixin'           => '微信',
            'profile.qq'               => 'QQ',
            'profile.school'           => '大学',
            'profile.major'            => '专业',
            'profile.is_qq_public'     => '是否公开QQ',
            'profile.is_weixin_public' => '是否公开微信',
            'profile.is_weibo_public'  => '是否公开微博',
            'profile.tags'             => '标签',
            'profile.idcard'           => '身份证',
            'profile.birthday'         => '生日',
            'profile.city'             => '城市',
        ];
    }
}
