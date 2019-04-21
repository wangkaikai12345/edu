<?php

namespace App\Http\Controllers\Backstage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Http\Transformers\CategoryTransformer;
use App\Models\Category;
use App\Models\CategoryGroup;
use Hashids;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * 子类页面
     *
     * @param CategoryGroup $categoryGroup
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(CategoryGroup $categoryGroup)
    {
        !request('parent_id') && request()->offsetSet('parent_id', 0);

        $categories = $categoryGroup->categories()->where(\request()->only('parent_id'))->sorted()->paginate(self::perPage());

        return view('admin.category.index_child', compact('categories', 'categoryGroup'));
    }


    /**
     * 子类创建
     *
     * @param CategoryGroup $categoryGroup
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(CategoryGroup $categoryGroup, Category $category)
    {
        return view('admin.category.create_child', compact('categoryGroup', 'category'));
    }


    /**
     * 添加子类
     *
     * @param CategoryGroup $categoryGroup
     * @param CategoryRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(CategoryGroup $categoryGroup, CategoryRequest $request)
    {
        $parentId = $request->parent_id ? Category::findOrFail($request->parent_id)->id : 0;

        $category = new Category($request->all());
        $category->category_group_id = $categoryGroup->id;
        $category->parent_id = $parentId;
        $category->save();

        return $this->response->item($category, new CategoryTransformer())->setStatusCode(201);
    }


    /**
     * 修改
     *
     * @param CategoryGroup $categoryGroup
     * @param $category
     * @return \Dingo\Api\Http\Response
     */
    public function edit(CategoryGroup $categoryGroup, $category)
    {
        $category = $categoryGroup->categories()->findOrFail($category);


        return view('admin.category.edit_child', compact('category', 'categoryGroup'));
    }


    /**
     * 更新
     *
     * @param CategoryGroup $categoryGroup
     * @param $category
     * @param CategoryRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function update(CategoryGroup $categoryGroup, $category, CategoryRequest $request)
    {
        $item = $categoryGroup->categories()->findOrFail($category);

        $item->fill($request->all());
        $item->save();

        return $this->response->noContent();
    }


    public function destroy(CategoryRequest $request, CategoryGroup $categoryGroup)
    {
        $categoryGroup->categories()->whereIn('id', $request->ids)->delete();

        return $this->response->noContent();
    }


    /**
     * 验证唯一性
     *
     * @param CategoryGroup $categoryGroup
     * @param Request $request
     * @return string
     */
    public function verifyFieldUniqueness(CategoryGroup $categoryGroup, Request $request)
    {
        $this->validate($request, ['key' => 'required|string', 'category_id' => 'nullable|string']);

        // 获取传递的值
        if (empty($value = $request->input($request->input('key')))) {
            return json_encode(['valid' => true]);
        }

        // 构建查询语句
        $query = $categoryGroup->categories()->where($request->input('key'), $value);

        // 判断_id是否存在
        if (!empty($category_id = $request->input('category_id'))) {
            $query->where('id', '<>', $category_id);
        }

        // 查询是否重复
        if ($query->exists()) {
            return json_encode(['valid' => false]);
        }

        // 验证通过
        return json_encode(['valid' => true]);
    }
}
