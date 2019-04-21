<?php

namespace App\Http\Controllers\Admin\Permission;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Permission\RolePermissionRequest;
use App\Http\Transformers\PermissionTransformer;
use App\Models\Permission;
use App\Models\Role;

class RolePermissionController extends Controller
{
    /**
     * @SWG\Get(
     *  path="/admin/roles/{role_id}/permissions",
     *  tags={"permission"},
     *  summary="角色权限列表",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="role_id",in="path",required=true,type="integer",description="角色 ID"),
     *  @SWG\Response(response=200,ref="#/responses/PermissionResponse"),
     *  security={
     *      {"Bearer": {}}
     *  }
     * )
     */
    public function index(Role $role)
    {
        $data = $role->permissions()->get();

        return $this->response->collection($data, new PermissionTransformer());
    }

    /**
     * @SWG\Put(
     *  path="/admin/roles/{role_id}/permissions",
     *  tags={"permission"},
     *  summary="角色权限添加/更新",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="role_id",in="path",required=true,type="integer",description="角色 ID"),
     *  @SWG\Parameter(name="permission_ids",in="formData",type="array",@SWG\Items(type="integer"),description="权限ID数组"),
     *  @SWG\Response(response=204,description="",ref="#/responses/PermissionResponse"),
     *  security={
     *      {"Bearer": {}}
     *  }
     * )
     */
    public function update(Role $role, Permission $permission, RolePermissionRequest $request)
    {
        $permissions = $permission->whereIn('id', $request->permission_ids)->get();

        $role->syncPermissions($permissions);

        return $this->response->noContent();
    }
}
