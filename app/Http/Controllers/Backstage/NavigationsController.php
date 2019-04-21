<?php

namespace App\Http\Controllers\Backstage;

use App\Http\Requests\Backstage\NavigationRequest;
use App\Models\Navigation;
use Dingo\Api\Http\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NavigationsController extends Controller
{
    public function headIndex()
    {
        $heads = Navigation::head()->with('children')->get();

        return view('admin.setting.head', compact('heads'));
    }

    /**
     * 顶部导航添加
     *
     * @param Navigation $navigation
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function headCreate(Navigation $navigation)
    {
        return view('admin.setting.head_create_and_edit', compact('navigation'));
    }


    /**
     * 头部导航创建
     *
     * @param NavigationRequest $request
     * @return Response
     */
    public function headStore(NavigationRequest $request)
    {
        return $this->store($request, 'header');
    }


    /**
     * 导航修改
     *
     * @param Navigation $navigation
     * @return Response
     */
    public function edit(Navigation $navigation)
    {
        return view('admin.setting.head_create_and_edit', compact('navigation'));
    }


    /**
     * 导航更新
     *
     * @param Navigation $navigation
     * @param NavigationRequest $request
     * @return Response
     */
    public function update(Navigation $navigation, NavigationRequest $request)
    {
        $navigation->update($request->only('name', 'link', 'target', 'status'));

        return $this->response->noContent();
    }

    /**
     * 底部导航
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function footerIndex()
    {
        $heads = Navigation::footer()->with('children')->get();

        return view('admin.setting.footer', compact('heads'));
    }


    /**
     * 顶部导航添加
     *
     * @param Navigation $navigation
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function footerCreate(Navigation $navigation)
    {
        return view('admin.setting.footer_create_and_edit', compact('navigation'));
    }


    /**
     * 底部导航创建
     *
     * @param NavigationRequest $request
     * @return Response
     */
    public function footerStore(NavigationRequest $request)
    {
        return $this->store($request, 'footer');
    }


    /**
     * 添加
     *
     * @param Request $request
     * @param $type
     * @return Response
     */
    protected function store(Request $request, $type)
    {
        $request->offsetSet('type', $type);

        Navigation::create($request->only(['name', 'link', 'status', 'target', 'type']));

        return new Response([], 201, []);
    }


    /**
     * 添加子导航
     *
     * @param Navigation $navigation
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createChild(Navigation $navigation)
    {
        $parent = $navigation;

        return view('admin.setting.head_create_and_edit', compact('parent'));
    }


    /**
     * 添加子导航
     *
     * @param Navigation $navigation
     * @param NavigationRequest $request
     * @return Response
     */
    public function storeChild(Navigation $navigation, NavigationRequest $request)
    {
        $request->offsetSet('type', $navigation->type);

        $navigation->children()->create($request->only(['name', 'link', 'status', 'target', 'type']));

        return new Response([], 201, []);
    }


    /**
     * 删除
     *
     * @param Navigation $navigation
     * @return Response|void
     * @throws \Exception
     */
    public function destroy(Navigation $navigation)
    {
        if ($navigation->children()->exists()) {
            return $this->response->errorForbidden('存在子类无法删除.');
        }

        $navigation->delete();

        return $this->response->noContent();
    }

    /**
     * 验证唯一性
     *
     * @param Request $request
     * @return string
     */
    public function verifyFieldUniqueness(Request $request)
    {
        $this->validate($request, ['key' => 'required|string', 'navigation_id' => 'nullable|string', 'parent_id' => 'nullable|string', 'type'=> 'required']);

        // 获取传递的值
        if (empty($value = $request->input($request->input('key')))) {
            return json_encode(['valid' => true]);
        }

        // 构建查询语句
        $query = Navigation::where($request->input('key'), $value)->where('type', $request->input('type'));

        // 判断parent_id是否存在
        if (!empty($parent_id = $request->input('parent_id'))) {
            $query->where(compact('parent_id'));
        }

        // 判断navigation_id是否存在
        if (!empty($navigation_id = $request->input('navigation_id'))) {
            $query->where('id', '<>', $navigation_id);
        }

        // 查询是否重复
        if ($query->exists()) {
            return json_encode(['valid' => false]);
        }

        // 验证通过
        return json_encode(['valid' => true]);
    }
}
