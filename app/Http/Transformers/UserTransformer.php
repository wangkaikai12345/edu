<?php

namespace App\Http\Transformers;

use App\Enums\SettingType;
use App\Models\Follow;
use Facades\App\Models\Setting;
use App\Models\User;

class UserTransformer extends BaseTransformer
{
    /**
     * @var string 域名
     */
    public $domain;
    /**
     * @var string 头像
     */
    private $avatar;
    /**
     * @var array
     */
    protected $availableIncludes = ['roles', 'profile'];

    public function __construct()
    {
        $this->domain = Setting::namespace(SettingType::QINIU)['public_domain'];
        $this->avatar = Setting::namespace(SettingType::AVATAR)['image'];
    }

    /**
     * @var array
     */
    protected $defaultIncludes = [];

    public function transform(User $model)
    {
        return [
            'id' => $model->id,
            'username' => $model->username,
            'email' => $model->email ? hide_info($model->email) : null,
            'phone' => $model->phone ? hide_info($model->phone) : null,
            'coin' => $model->coin,
            'avatar' => $model->avatar ? $this->domain . '/' . $model->avatar : $this->avatar,
            'signature' => $model->signature,
            'is_email_verified' => (boolean)$model->is_email_verified,
            'is_phone_verified' => (boolean)$model->is_phone_verified,
            'tags' => $model->tags,
            'registered_ip' => $model->registered_ip,
            'registered_way' => $model->registered_way,
            'locked' => (boolean)$model->locked,
            'locked_deadline' => $model->locked_deadline ? $model->locked_deadline->toDateTimeString() : null,
            'password_error_times' => $model->password_error_times,
            'last_password_failed_at' => $model->last_password_failed_at ? $model->last_password_failed_at->toDateTimeString() : null,
            'last_logined_at' => $model->last_logined_at ? $model->last_logined_at->toDateTimeString() : null,
            'last_logined_ip' => $model->last_logined_ip,
            'new_messages_count' => (int)$model->new_messages_count,
            'new_notifications_count' => (int)$model->new_notifications_count,
            'invitation_code' => $model->invitation_code,
            'inviter_id' => $model->inviter_id,
            'is_recommended' => (bool)$model->is_recommended,
            'recommended_seq' => (int)$model->recommended_seq,
            'followers_count' => $model->followers_count ?? 0,
            'fans_count' => $model->fans_count ?? 0,
            'plans_count' => $model->plans_count,
            'is_followed' => $this->isFollowed($model),
            'created_at' => $model->created_at ? $model->created_at->toDateTimeString() : null,
            'updated_at' => $model->updated_at ? $model->updated_at->toDateTimeString() : null,
        ];
    }

    /**
     * 用户详细
     *
     * @param User $model
     * @return \League\Fractal\Resource\Collection|\League\Fractal\Resource\Item|\League\Fractal\Resource\NullResource
     */
    public function includeProfile(User $model)
    {
        return $this->setDataOrItem($model->profile, new ProfileTransformer());
    }

    /**
     * 角色
     *
     * @param User $model
     * @return \League\Fractal\Resource\Collection|\League\Fractal\Resource\Item|\League\Fractal\Resource\NullResource
     */
    public function includeRoles(User $model)
    {
        $roles = $model->roles()->select(['id', 'name', 'title'])->get();

        return $this->setDataOrItem($roles, new RoleTransformer());
    }

    /**
     * 是否关注
     */
    public function isFollowed(User $model)
    {
        $me = auth()->user();
        if (!$me) {
            return false;
        }

        if ($model->id == $me->id) {
            return false;
        }

        return Follow::where('user_id', $me->id)->where('follow_id', $model->id)->exists();
    }
}