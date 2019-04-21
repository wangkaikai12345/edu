<?php
/**
 * Created by PhpStorm.
 * Category: wangbaolong
 * Date: 2018/4/18
 * Time: 10:22
 */

namespace App\Http\Transformers;

use App\Enums\SettingType;
use Facades\App\Models\Setting;
use App\Models\User;
use League\Fractal\TransformerAbstract;

class MessageUserTransformer extends TransformerAbstract
{
    /**
     * @var string 域名
     */
    public $domain;
    /**
     * @var string 头像
     */
    private $avatar;

    public function __construct()
    {
        $this->domain = Setting::namespace(SettingType::QINIU)['public_domain'];
        $this->avatar = Setting::namespace(SettingType::AVATAR)['image'];
    }

    public function transform(User $model)
    {
        return [
            'id' => $model->id,
            'username' => $model->username,
            'avatar' => $model->avatar ? $this->domain . '/' . $model->avatar : $this->avatar,
            'signature' => $model->signature,
        ];
    }
}