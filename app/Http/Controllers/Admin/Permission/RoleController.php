<?php

namespace App\Http\Controllers\Admin\Permission;

use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Permission\RoleRequest;
use App\Http\Transformers\RoleTransformer;
use App\Events\PermissionCacheClearEvent;
use App\Models\Role;

class RoleController extends Controller
{
    /**
     * @SWG\Tag(name="role",description="角色")
     */

    /**
     * @SWG\Get(
     *  path="/admin/roles",
     *  tags={"role"},
     *  summary="角色列表",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(ref="#/parameters/per_page"),
     *  @SWG\Parameter(ref="#/parameters/page"),
     *  @SWG\Response(response=200,ref="#/responses/RolePagination"),
     *  security={
     *      {"Bearer": {}}
     *  }
     * )
     */
    public function index(Role $role)
    {
        $data = $role->paginate(self::perPage());

        return $this->response->paginator($data, new RoleTransformer());
    }

    /**
     * @SWG\Get(
     *  path="/admin/roles/{role_id}",
     *  tags={"role"},
     *  summary="角色详情",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="role_id",in="path",type="integer",description="角色 ID"),
     *  @SWG\Response(response=200,description="",@SWG\Schema(ref="#/definitions/Role")),
     *  security={
     *      {"Bearer": {}}
     *  }
     * )
     */
    public function show(Role $role)
    {
        return $this->response->item($role, new RoleTransformer());
    }

    /**
     * @SWG\Post(
     *  path="/admin/roles",
     *  tags={"role"},
     *  summary="角色添加",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="name",in="formData",type="string",description="英文名称"),
     *  @SWG\Parameter(name="title",in="formData",type="string",description="中文名称"),
     *  @SWG\Response(response=201,description="",@SWG\Schema(ref="#/definitions/Role")),
     *  security={
     *      {"Bearer": {}}
     *  }
     * )
     */
    public function store(Role $role, RoleRequest $request)
    {
        $role->fill($request->only(['name', 'title']));
        $role->guard_name = 'api';
        $role->save();

        event(new PermissionCacheClearEvent());

        return $this->response->item($role, new RoleTransformer())->setStatusCode(201);
    }

    /**
     * @SWG\Put(
     *  path="/admin/roles/{role_id}",
     *  tags={"role"},
     *  summary="角色更新",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="role_id",in="path",type="integer",description="角色 ID"),
     *  @SWG\Parameter(name="name",in="formData",type="string",description="英文名称"),
     *  @SWG\Parameter(name="title",in="formData",type="string",description="中文名称"),
     *  @SWG\Response(response=204,description=""),
     *  security={
     *      {"Bearer": {}}
     *  }
     * )
     */
    public function update(Role $role, RoleRequest $request)
    {
        // 初始化角色无法更新
        if (in_array($role->name, UserType::getValues())) {
            abort(403, __('Initial roles cannot be updated or deleted.'));
        }

        $role->fill($request->only(['name', 'title']));
        $role->guard_name = 'api';
        $role->save();

        event(new PermissionCacheClearEvent());

        return $this->response->noContent();
    }

    /**
     * @SWG\Delete(
     *  path="/admin/roles/{role_id}",
     *  tags={"role"},
     *  summary="角色删除",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="role_id",in="path",type="integer",description="角色 ID"),
     *  @SWG\Response(response=204,description=""),
     *  security={
     *      {"Bearer": {}}
     *  }
     * )
     */
    public function destroy(Role $role)
    {
        // 初始化角色无法
        if (in_array($role->name, UserType::getValues())) {
            abort(403, __('Initial roles cannot be updated or deleted.'));
        }

        $role->delete();

        event(new PermissionCacheClearEvent());

        return $this->response->noContent();
    }
}
