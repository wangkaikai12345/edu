<?php

namespace App\Http\Controllers\Front;

use App\Models\Note;
use App\Http\Controllers\Controller;
use App\Http\Requests\Front\NoteRequest;
use App\Models\Task;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    /**
     * 任务下的笔记列表
     *
     * @param Task $task
     * @return \Illuminate\Http\JsonResponse
     * @author 王凯
     */
    public function index(Request $request, Task $task)
    {
        // 数据验证，是否是版本的成员
        if (!$plan = $task->plan) return ajax('400', '版本任务不存在');

//        if (!$plan->isMember()) return ajax('400', '您还未加入课程版本');

        if ($request->my == 'self') {
            $notes = $task->notes()
                ->where('is_public', true)
                ->where('user_id', auth('web')->id())
                ->orderBy('collection')
                ->paginate(5);
        } else {
            // 查询任务下的笔记
            $notes = $task->notes()->where('is_public', true)->orderBy('collection')->paginate(5);
        }

        return ajax('200', '获取成功', $notes);
    }

    /**
     * 保存笔记
     *
     * @param NoteRequest $request
     * @param Task $task
     * @return $this
     * @author 王凯
     */
    public function store(NoteRequest $request, Task $task)
    {
        // 数据验证，是否是版本的成员
        if (!$plan = $task->plan) return ajax('400', '版本任务不存在');

//        if (!$plan->isMember()) return ajax('400', '您还未加入课程版本');

        $note = new Note($request->all());
        $note->course_id = $plan->course_id;
        $note->plan_id = $plan->id;
        $note->task_id = $task->id;
        $note->user_id = auth('web')->id();
        $note->save();

        return ajax('200', '保存成功');
    }

    public function update(Task $task, Note $note)
    {
        if (!$plan = $task->plan) return ajax('400', '版本任务不存在');

//        if (!$plan->isMember()) return ajax('400', '您还未加入课程版本');


    }

}
