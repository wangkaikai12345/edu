<?php

namespace App\Http\Controllers\Backstage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backstage\TagRequest;
use App\Http\Transformers\TagTransformer;
use App\Models\Tag;
use App\Models\TagGroup;
use App\Models\ModelHasTag;
use DB;
use Illuminate\Http\Request;

class TagController extends Controller
{

    /**
     * 添加
     *
     * @param TagGroup $tagGroup
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(TagGroup $tagGroup)
    {
        return view('admin.tag.create_child', compact('tagGroup'));
    }


    /**
     * 保存
     *
     * @param TagRequest $request
     * @param TagGroup $tagGroup
     * @return \Dingo\Api\Http\Response
     */
    public function store(TagRequest $request, TagGroup $tagGroup)
    {
        $input = $request->only(['name']);
        $item = $tagGroup->tags()->create($input);

        return $this->response->item($item, new TagTransformer())->setStatusCode(201);
    }


    /**
     * 添加
     *
     * @param TagGroup $tagGroup
     * @param $tag
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(TagGroup $tagGroup, $tag)
    {
        $tag = $tagGroup->tags()->findOrFail($tag);

        return view('admin.tag.edit_child', compact('tagGroup', 'tag'));
    }

    /**
     * 更新
     *
     * @param TagRequest $request
     * @param TagGroup $tagGroup
     * @param $tag
     * @return \Dingo\Api\Http\Response
     */
    public function update(TagRequest $request, TagGroup $tagGroup, $tag)
    {
        $tag = $tagGroup->tags()->findOrFail($tag);

        $input = $request->only(['name']);
        $tag->fill($input);
        $tag->save();

        return $this->response->noContent();
    }

    /**
     * 删除
     *
     * @param TagGroup $tagGroup
     * @param $tag
     * @return \Dingo\Api\Http\Response
     * @throws \Throwable
     */
    public function destroy(TagGroup $tagGroup, $tag)
    {
        $tag = $tagGroup->tags()->findOrFail($tag);
        // 去除 model_has_tags、tags、tag_group_tags
        DB::transaction(function () use ($tagGroup, $tag) {
            ModelHasTag::where('tag_id', $tag->id)->delete();
            $tag->delete();
        });

        return $this->response->noContent();
    }


    /**
     * 验证唯一性
     *
     * @param TagGroup $tagGroup
     * @param Request $request
     * @return string
     */
    public function verifyFieldUniqueness(TagGroup $tagGroup, Request $request)
    {
        $this->validate($request, ['key' => 'required|string', 'tag_id' => 'nullable|string']);

        // 获取传递的值
        if (empty($value = $request->input($request->input('key')))) {
            return json_encode(['valid' => true]);
        }

        // 构建查询语句
        $query = $tagGroup->tags()->where($request->input('key'), $value);

        // 判断_id是否存在
        if (!empty($tag_id = $request->input('tag_id'))) {
            $query->where('id', '<>', $tag_id);
        }

        // 查询是否重复
        if ($query->exists()) {
            return json_encode(['valid' => false]);
        }

        // 验证通过
        return json_encode(['valid' => true]);
    }



//    public function show(TagGroup $tagGroup, $tag)
//    {
//        $tag = $tagGroup->tags()->findOrFail($tag);
//
//        return $this->response->item($tag, new TagTransformer());
//    }

//
//
//    public function batchDelete(TagGroup $tagGroup, TagRequest $request)
//    {
//        $ids = $request->ids;
//
//        DB::transaction(function () use ($ids, $tagGroup) {
//            ModelHasTag::whereIn('tag_id', $ids)->delete();
//            // 将标签移入回收站
//            Tag::whereIn('id', $ids)->delete();
//        });
//
//        return $this->response->noContent();
//    }
//
//    public function index(TagGroup $tagGroup)
//    {
//        $data = $tagGroup->tags()->sorted()->get();
//
//        return $this->response->collection($data, new TagTransformer());
//    }
}
