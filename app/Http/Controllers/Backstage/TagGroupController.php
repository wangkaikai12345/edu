<?php

namespace App\Http\Controllers\Backstage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backstage\TagGroupRequest;
use App\Http\Transformers\TagGroupTransformer;
use App\Models\TagGroup;
use Illuminate\Http\Request;

class TagGroupController extends Controller
{

    /**
     * 首页
     *
     * @param TagGroup $tagGroup
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(TagGroup $tagGroup)
    {
        $tagGroups = $tagGroup->sorted()->with(['tags'])->get();

        return view('admin.tag.index', compact('tagGroups'));
    }


    /**
     * 添加
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.tag.create');
    }


    /**
     * 保存
     *
     * @param TagGroupRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(TagGroupRequest $request)
    {
        $group = new TagGroup();
        $group->fill($request->all());
        $group->save();

        return $this->response->item($group, new TagGroupTransformer());
    }


    /**
     * 保存
     *
     * @param TagGroup $tagGroup
     * @return \Dingo\Api\Http\Response
     */
    public function edit(TagGroup $tagGroup)
    {
        return view('admin.tag.edit', compact('tagGroup'));
    }


    /**
     * 更新
     *
     * @param TagGroupRequest $request
     * @param TagGroup $tagGroup
     * @return \Dingo\Api\Http\Response
     */
    public function update(TagGroupRequest $request, TagGroup $tagGroup)
    {
        $tagGroup->fill($request->all());
        $tagGroup->save();

        return $this->response->item($tagGroup, new TagGroupTransformer());
    }



    /**
     * 验证唯一性
     *
     * @param Request $request
     * @return string
     */
    public function verifyFieldUniqueness(Request $request)
    {
        $this->validate($request, ['key' => 'required|string', 'tag_group_id' => 'nullable|string']);

        // 获取传递的值
        if (empty($value = $request->input($request->input('key')))) {
            return json_encode(['valid' => true]);
        }

        // 构建查询语句
        $query = TagGroup::where($request->input('key'), $value);

        // 判断_id是否存在
        if (!empty($tag_group_id = $request->input('tag_group_id'))) {
            $query->where('id', '<>', $tag_group_id);
        }

        // 查询是否重复
        if ($query->exists()) {
            return json_encode(['valid' => false]);
        }

        // 验证通过
        return json_encode(['valid' => true]);
    }

//
//    public function show(TagGroup $tagGroup)
//    {
//        return $this->response->item($tagGroup, new TagGroupTransformer());
//    }
//
//
//    public function destroy(TagGroup $tagGroup)
//    {
//        if ($tagGroup->tags()->exists()) {
//            $this->response->errorBadRequest(__('Child node exists.'));
//        }
//        $tagGroup->delete();
//
//        return $this->response->noContent();
//    }

}
