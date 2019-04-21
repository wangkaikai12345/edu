<?php

namespace App\Http\Controllers\Admin\Permission;

use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Permission\UserRoleRequest;
use App\Http\Transformers\RoleTransformer;
use App\Models\User;
use App\Models\Role;

class UserRoleController extends Controller
{
    /**
     * @SWG\Get(
     *  path="/admin/users/{user_id}/roles",
     *  tags={"role"},
     *  summary="用户角色列表",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="user_id",in="path",type="integer",description="用户 ID"),
     *  @SWG\Response(response=200,ref="#/responses/RoleResponse"),
     *  security={
     *      {"Bearer": {}}
     *  }
     * )
     */
    public function index(User $user)
    {
        $data = $user->roles()->get();

        return $this->response->collection($data, new RoleTransformer());
    }

    /**
     * @SWG\Put(
     *  path="/admin/users/{user_id}/roles",
     *  tags={"role"},
     *  summary="用户角色添加/更新",
     *  description="用于为用户更新角色信息，无则添加，有则添加。",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="user_id",in="path",type="integer",description="用户 ID"),
     *  @SWG\Parameter(name="role_id",in="formData",type="integer",description="角色ID"),
     *  @SWG\Response(response=204,ref="#/responses/RoleResponse"),
     *  security={
     *      {"Bearer": {}}
     *  }
     * )
     */
    public function update(User $user, UserRoleRequest $request, Role $role)
    {
        // ID 为 1 的超管无法删除超管角色
        if ($user->id == 1) {
            abort(403, __('You can not edit your self.'));
        }

        $roles = $role->find($request->role_id);

        if ($roles->name == UserType::SUPER_ADMIN) {
            abort(403, __('There can only be one Super admin.'));
        }

        $user->syncRoles($roles);

        return $this->response->noContent();
    }
}
