<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionRule implements Rule
{
    /**
     * @var Role
     */
    private $role;

    /**
     * @var string
     */
    private $message = '';

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Role $role)
    {
        $this->role = $role;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // 查询权限是否在数据库中
        $ids = Permission::whereIn('id', $value)->pluck('id')->toArray();

        if (count($ids) !== count($value)) {
            $ids = array_diff($value, $ids);
            $notExistPermissions = Permission::whereIn('id', $ids)->pluck('name')->get();
            $this->message = '部分权限不存在或已被异动：' . $notExistPermissions->implode('name', ',');
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
