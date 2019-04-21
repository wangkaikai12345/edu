<?php

namespace App\Http\Controllers\Admin\Permission;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Permission\PermissionRequest;
use App\Http\Transformers\PermissionTransformer;
use App\Events\PermissionCacheClearEvent;
use App\Models\Permission;

class PermissionController extends Controller
{
    /**
     * @SWG\Tag(name="permission",description="权限")
     */

    /**
     * @SWG\Get(
     *  path="/admin/permissions",
     *  tags={"permission"},
     *  summary="权限列表",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(ref="#/parameters/per_page"),
     *  @SWG\Parameter(ref="#/parameters/page"),
     *  @SWG\Response(response=200,description="ok",ref="#/definitions/PermissionTree"),
     *  security={
     *      {"Bearer": {}}
     *  }
     * )
     */
    public function index(Permission $permission)
    {
        // 以树形结构返回
        $data = config('permission.data');

        $preData = collect($data)->pluck('label', 'prefix')->map(function ($item, $key) {
            return collect([
                'label'  => $item,
                'prefix' => $key,
                'items'  => [],
            ]);
        })->toArray();

        $permissions = $permission->get(['id', 'title', 'name'])->toArray();

        foreach ($permissions as $permission) {
            $prefix = substr($permission['name'], 0, strrpos($permission['name'], '.'));

            $preData[$prefix]['items'][] = $permission;
        }


        return $this->response->array([
            'data' => array_values($preData),
        ]);
    }

    /**
     * @SWG\Post(
     *  path="/admin/permissions",
     *  tags={"permission"},
     *  summary="权限添加",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="name",in="formData",type="string",description="英文名称"),
     *  @SWG\Parameter(name="title",in="formData",type="string",description="中文名称"),
     *  @SWG\Parameter(name="is_menu",in="formData",type="boolean",description="是否为菜单"),
     *  @SWG\Response(response=201,description="",@SWG\Schema(ref="#/definitions/Permission")),
     *  security={
     *      {"Bearer": {}}
     *  }
     * )
     */
    public function store(Permission $permission, PermissionRequest $request)
    {
        $permission->create($request->all());
        $permission->save();

        event(new PermissionCacheClearEvent());

        return $this->response->item($permission, new PermissionTransformer())->setStatusCode(201);
    }

    /**
     * @SWG\Put(
     *  path="/admin/permissions/{permission_id}",
     *  tags={"permission"},
     *  summary="权限更新",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="permission_id",in="path",type="integer",description="权限 ID"),
     *  @SWG\Parameter(name="name",in="formData",type="string",description="英文名称"),
     *  @SWG\Parameter(name="title",in="formData",type="string",description="中文名称"),
     *  @SWG\Parameter(name="is_menu",in="formData",type="boolean",description="是否为菜单"),
     *  @SWG\Response(response=204,description="ok"),
     *  security={
     *      {"Bearer": {}}
     *  }
     * )
     */
    public function update(Permission $permission, PermissionRequest $request)
    {
        $permission->fill($request->all());
        $permission->save();

        event(new PermissionCacheClearEvent());

        return $this->response->noContent();
    }

    /**
     * @SWG\Delete(
     *  path="/admin/permissions/{permission_id}",
     *  tags={"permission"},
     *  summary="权限删除",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="permission_id",in="path",type="integer",description="权限 ID"),
     *  @SWG\Response(response=204,description="ok"),
     *  security={
     *      {"Bearer": {}}
     *  }
     * )
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();

        event(new PermissionCacheClearEvent());

        return $this->response->noContent();
    }
}
