<?php

namespace App\Http\Controllers\Backstage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backstage\CategoryGroupRequest;
use App\Http\Transformers\CategoryGroupTransformer;
use App\Models\CategoryGroup;
use Illuminate\Http\Request;

class CategoryGroupController extends Controller
{

    /**
     * 分类分组查询
     *
     * @param CategoryGroup $categoryGroup
     * @return \Dingo\Api\Http\Response
     */
    public function index(CategoryGroup $categoryGroup)
    {
        $categoryGroups = $categoryGroup->sorted()->filtered()->with(['categories' => function ($query) {
            $query->where('parent_id', 0);
        }])->get();

        return view('admin.category.index', compact('categoryGroups'));
    }


    /**
     * 分类分组添加
     *
     * @return \Dingo\Api\Http\Response
     */
    public function create()
    {
        return view('admin.category.create');
    }


    /**
     * 分类分组添加
     *
     * @param CategoryGroupRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(CategoryGroupRequest $request)
    {
        $data = CategoryGroup::create($request->only(['name', 'title']));

        return $this->response->item($data, new CategoryGroupTransformer())->setStatusCode(201);
    }


    /**
     * 分类分组修改
     *
     * @param CategoryGroup $categoryGroup
     * @return \Dingo\Api\Http\Response
     */
    public function edit(CategoryGroup $categoryGroup)
    {
        return view('admin.category.edit', compact('categoryGroup'));
    }


    /**
     * 分类分组修改
     *
     * @param CategoryGroup $categoryGroup
     * @param CategoryGroupRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function update(CategoryGroup $categoryGroup, CategoryGroupRequest $request)
    {
        $categoryGroup->name = $request->input('name');
        $categoryGroup->title = $request->input('title');
        $categoryGroup->save();

        return $this->response->noContent();
    }


    /**
     * 详情
     *
     * @param CategoryGroup $categoryGroup
     * @return \Dingo\Api\Http\Response
     */
    public function show(CategoryGroup $categoryGroup)
    {
        $categories = $categoryGroup->categories()->withCount('children')->where('parent_id', 0)->get();

        return view('admin.category.show', compact('categories'));
    }


    /**
     * 验证唯一性
     *
     * @param Request $request
     * @return string
     */
    public function verifyFieldUniqueness(Request $request)
    {
        $this->validate($request, ['key' => 'required|string', 'category_group_id' => 'nullable|string']);

        // 获取传递的值
        if (empty($value = $request->input($request->input('key')))) {
            return json_encode(['valid' => true]);
        }

        // 构建查询语句
        $query = CategoryGroup::where($request->input('key'), $value);

        // 判断_id是否存在
        if (!empty($category_group_id = $request->input('category_group_id'))) {
            $query->where('id', '<>', $category_group_id);
        }

        // 查询是否重复
        if ($query->exists()) {
            return json_encode(['valid' => false]);
        }

        // 验证通过
        return json_encode(['valid' => true]);
    }
}
