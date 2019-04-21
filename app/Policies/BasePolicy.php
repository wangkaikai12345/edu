<?php
/**
 * Created by PhpStorm.
 * User: wangbaolong
 * Date: 2018/7/2
 * Time: 16:59
 */

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BasePolicy
{
    use HandlesAuthorization;

    /**
     * @var string
     */
    protected $message = '无操作权限，请求被拒绝。';

    /**
     * 过滤器
     *
     * @param User $user
     * @param $ability
     * @return bool
     */
    public function before(User $user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * 通用响应
     *
     * @param bool $bool
     * @param string|null $message
     * @return bool
     */
    public function trueOrThrow($bool = true, string $message = null)
    {
        $this->message = $message ?? $this->message;
        if (!$bool) {
            abort(403, $this->message);
        }
        return $bool;
    }

    /**
     * 设置自定义信息
     *
     * @param string $message
     * @return void
     */
    public function message($message = '无相关权限，请求被拒绝。')
    {
        $this->message = $message;
    }
}