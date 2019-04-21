<?php

namespace App\Http\Controllers\Front\Manage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\ChapterRequest;
use App\Models\Chapter;
use App\Models\Plan;
use App\Models\Task;
use DB;
use Illuminate\Http\Request;

class ChapterController extends Controller
{
    // 章节列表
    public function index(Plan $plan)
    {
        $data = $plan->chapters()->orderBy('seq')->where(['parent_id' => 0])->get();

    }

    /**
     * 创建章节
     */
    public function store(Plan $plan, ChapterRequest $request)
    {
        // 查询最大排序顺序
        $maxSeq = $plan->chapters()->where('parent_id', request('parent_id', 0))->max('seq') ?? 0;
        $chapter = new Chapter($request->all());
        $chapter->course_id = $plan->course_id;
        $chapter->plan_id = $plan->id;
        $chapter->user_id = auth('web')->id();
        $chapter->seq = $maxSeq + 1;
        $chapter->parent_id = request('parent_id', 0);
        $chapter->save();

        return ajax('200', '添加成功!');
    }

    /**
     * 章节编辑
     *
     * @param Plan $plan
     * @param Chapter $chapter
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author 王凯
     */
    public function edit(Plan $plan, Chapter $chapter)
    {
        return view('teacher.plan.modal.edit-chapter-modal', compact('plan','chapter'));
    }

    /**
     * 章节标题和目标的更新
     *
     * @param Plan $plan
     * @param Chapter $chapter
     * @param ChapterRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @author 王凯
     */
    public function update(Plan $plan, Chapter $chapter, ChapterRequest $request)
    {

        $chapter->title = $request->title;
        $chapter->goals = $request->goals;

        $chapter->save();

        return ajax('200', '编辑成功!');
    }

    // 删除章节
    public function destroy(Plan $plan, $chapterId)
    {
        $chapter = $plan->chapters()->findOrFail($chapterId);

        // 如果是章，则检查是存在节
        if ($chapter->parent_id == 0 && $chapter->children()->count()) {
            return ajax('400', __('Child node exists.'));
        } // 如果是节，则检查节下是否存在任务
        else if ($chapter->parent_id != 0 && $chapter->tasks()->count()) {
            return ajax('400', __('Child node exists.'));
        }

        $chapter->delete();

        return ajax('200', '删除成功');

    }

    /**
     * 章节或者任务排序
     */
    public function sort(Request $request, $type)
    {
        $ids = collect($request->ids);
        if ($type == 'chapter') {
            $targets = Chapter::whereIn('id', $ids)->get();
        } else if ($type == 'task') {
            $targets = Task::whereIn('id', $ids)->get();
        } else {
            return ajax('400', '非法请求!');
        }

        $targets->each(function ($c) use ($ids) {
            $c->seq = $ids->search($c->id) + 1;
            $c->save();
        });

        return ajax('200', '排序成功!');
    }

    // 章节排序
//    public function sort(Plan $plan, ChapterRequest $request)
//    {
//        $parentId = (int)$request->parent_id;
//        $targetId = (int)$request->target_id;
//        $beforeId = $request->input('before_id', false);
//
//        switch ($request->type) {
//            case 'chapter':
//            case 'section':
//                $chapters = Chapter::where('plan_id', $plan->id)
//                    ->where('parent_id', $parentId)
//                    ->orderBy('seq')->pluck('id')->toArray();
//
//                $targetIndex = array_search($targetId, $chapters);
//
//                // 移除目标
//                array_splice($chapters, $targetIndex, 1);
//                if ($beforeId){
//                    // 移动到目标位置
//                    $beforeIndex = array_search($beforeId, $chapters);
//                    array_splice($chapters, $beforeIndex, 0, $targetId);
//                } else {
//                    // 移动到最后位置
//                    array_push($chapters, $targetId);
//                }
//                DB::transaction(function () use ($chapters) {
//                    foreach ($chapters as $index => $id) {
//                        Chapter::where('id', $id)->update(['seq' => $index + 1]);
//                    }
//                });
//                break;
//            case 'task':
//                $tasks = Chapter::find($parentId)->tasks()->orderBy('seq')->pluck('id')->toArray();
//
//                $targetIndex = array_search($targetId, $tasks);
//
//                // 移除目标
//                array_splice($tasks, $targetIndex, 1);
//
//                if ($beforeId){
//                    // 移动到指定位置
//                    $beforeIndex = array_search($beforeId, $tasks);
//                    array_splice($tasks, $beforeIndex, 0, $targetId);
//                } else {
//                    // 移动到最后位置
//                    array_push($tasks, $targetId);
//                }
//                DB::transaction(function () use ($tasks) {
//                    foreach ($tasks as $index => $id) {
//                        Task::where('id', $id)->update(['seq' => $index + 1]);
//                    }
//                });
//                break;
//        }
//
//    }
}
