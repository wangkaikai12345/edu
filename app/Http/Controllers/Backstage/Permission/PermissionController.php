<?php

namespace App\Http\Controllers\Backstage\Permission;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Permission\PermissionRequest;
use App\Http\Transformers\PermissionTransformer;
use App\Events\PermissionCacheClearEvent;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{

    /**
     * 权限页面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $permissions = Permission::parent()->with('children')->get();

        return view('admin.permission.index', compact('permissions'));
    }


    /**
     * 权限添加页面
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        $parent_id = $request->input('parent_id', 0);

        $permission = !empty($parent_id) ? Permission::findOrFail($parent_id) : new Permission();

        return view('admin.permission.create', compact('permission'));
    }


    /**
     * 权限保存
     *
     * @param Permission $permission
     * @param PermissionRequest $request
     * @return mixed
     * @throws \Throwable
     */
    public function store(Permission $permission, PermissionRequest $request)
    {
        if ($request->has('parent_id')) {
            $permission = Permission::findOrFail($request->input('parent_id'));
            $permission = $permission->children()->create($request->all());
        } else {
            $permission = $permission->create($request->all());
        }

        event(new PermissionCacheClearEvent());


        if ($request->has('parent_id')) {
            $view = view('admin.permission.child', compact('permission'))->render();
        } else {
            $permission->load('children');
            $view = view('admin.permission.parent', compact('permission'))->render();
        }

        return $this->response->array(['child' => $view, 'title' => $permission->title]);
    }


    /**
     * 修改页面
     *
     * @param Permission $permission
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Permission $permission)
    {
        return view('admin.permission.edit', compact('permission'));
    }


    /**
     * @param Permission $permission
     * @param PermissionRequest $request
     * @return mixed
     * @throws \Throwable
     */
    public function update(Permission $permission, PermissionRequest $request)
    {
        $permission->fill($request->all());
        $permission->save();

        event(new PermissionCacheClearEvent());

        if ($permission->parent_id == 0) {
            $view = view('admin.permission.edit_parent_tr', compact('permission'))->render();
        } else {
            $view = view('admin.permission.edit_child_tr', compact('permission'))->render();
        }

        return $this->response->array(['child' => $view, 'title' => $permission->title]);
    }

    /**
     * 权限删除
     *
     * @param Permission $permission
     * @return \Dingo\Api\Http\Response
     * @throws \Exception
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();

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
        $this->validate($request, ['key' => 'required|string', 'permission_id' => 'nullable|string']);

        // 获取传递的值
        if (empty($value = $request->input($request->input('key')))) {
            return json_encode(['valid' => true]);
        }

        // 构建查询语句
        $query = Permission::where($request->input('key'), $value);

        // 判断_id是否存在
        if (!empty($permission_id = $request->input('permission_id'))) {
            $query->where('id', '<>', $permission_id);
        }

        // 查询是否重复
        if ($query->exists()) {
            return json_encode(['valid' => false]);
        }

        // 验证通过
        return json_encode(['valid' => true]);
    }

}
