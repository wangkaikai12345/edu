<?php

namespace App\Http\Controllers\Backstage\Permission;

use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Permission\RoleRequest;
use App\Http\Transformers\RoleTransformer;
use App\Events\PermissionCacheClearEvent;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * 角色列表
     *
     * @param Role $role
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Role $role)
    {
        $roles = $role->paginate(self::perPage());

        $permissions = Permission::parent()->with('children')->get();


        return view('admin.role.index', compact('roles', 'permissions'));
    }

    /**
     * 创建
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $permissions = Permission::parent()->with('children')->get();

        return view('admin.role.create', compact('permissions'));
    }


    /**
     * 保存
     *
     * @param Role $role
     * @param RoleRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(Role $role, RoleRequest $request)
    {
        $role->fill($request->only(['name', 'title']));
        $role->save();

        $role->syncPermissions($request->permissions);

        event(new PermissionCacheClearEvent());

        return $this->response->item($role, new RoleTransformer())->setStatusCode(201);
    }

    /**
     * 修改
     *
     * @param Role $role
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Role $role)
    {
        // 权限信息
        $permissions = Permission::parent()->with('children')->get();

        // 角色拥有的权限
        $role_has_permissions = $role->permissions()->pluck('id')->toArray();

        // 返回页面
        return view('admin.role.edit', compact('permissions', 'role', 'role_has_permissions'));
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
        $role->save();
        $role->syncPermissions($request->permissions);

        event(new PermissionCacheClearEvent());

        return $this->response->noContent();
    }


    /**
     * 角色删除
     *
     * @param Role $role
     * @return \Dingo\Api\Http\Response
     * @throws \Exception
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


    /**
     * 唯一值验证
     *
     * @param Request $request
     * @return string
     */
    public function verifyFieldUniqueness(Request $request)
    {
        $this->validate($request, ['key' => 'required|string', 'role_id' => 'nullable|string']);

        // 获取传递的值
        if (empty($value = $request->input($request->input('key')))) {
            return json_encode(['valid' => true]);
        }

        // 构建查询语句
        $query = Role::where($request->input('key'), $value);

        // 判断_id是否存在
        if (!empty($role_id = $request->input('role_id'))) {
            $query->where('id', '<>', $role_id);
        }

        // 查询是否重复
        if ($query->exists()) {
            return json_encode(['valid' => false]);
        }

        // 验证通过
        return json_encode(['valid' => true]);
    }
}
