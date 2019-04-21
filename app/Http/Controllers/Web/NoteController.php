<?php

namespace App\Http\Controllers\Web;

use App\Models\Note;
use App\Models\Plan;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\NoteRequest;
use App\Http\Transformers\NoteTransformer;

class NoteController extends Controller
{
    /**
     * @SWG\Tag(name="web/note",description="笔记")
     */

    /**
     * @SWG\Get(
     *  path="/plans/{plan_id}/notes",
     *  tags={"web/note"},
     *  summary="列表",
     *  description="默认以创建时间倒序排序",
     *  @SWG\Parameter(name="plan_id",type="integer",in="path",required=true,description=""),
     *  @SWG\Parameter(ref="#/parameters/NoteQuery-task_id"),
     *  @SWG\Parameter(ref="#/parameters/NoteQuery-task:title"),
     *  @SWG\Parameter(ref="#/parameters/NoteQuery-user_id"),
     *  @SWG\Parameter(ref="#/parameters/NoteQuery-user:username"),
     *  @SWG\Parameter(ref="#/parameters/NoteQuery-is_public"),
     *  @SWG\Parameter(ref="#/parameters/Note-sort"),
     *  @SWG\Parameter(ref="#/parameters/Note-include"),
     *  @SWG\Parameter(ref="#/parameters/page"),
     *  @SWG\Parameter(ref="#/parameters/per_page"),
     *  @SWG\Response(response=200,description="ok",ref="#/responses/NotePagination"),
     *  @SWG\Response(response=401,ref="#/responses/AuthorizationException"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function index(Plan $plan)
    {
        $data = $plan->notes()->sorted()->paginate(self::perPage());

        return $this->response->paginator($data, new NoteTransformer());
    }

    /**
     * @SWG\Post(
     *  path="/plans/{plan_id}/notes",
     *  tags={"web/note"},
     *  summary="添加/更新",
     *  description="存在时即更新，否则创建",
     *  @SWG\Parameter(name="plan_id",type="integer",in="path",required=true,description="版本"),
     *  @SWG\Parameter(name="task_id",type="integer",in="formData",required=true,description="任务"),
     *  @SWG\Parameter(name="content",type="string",in="formData",required=true,description="笔记内容"),
     *  @SWG\Response(response=201,description="ok",ref="#/definitions/Note"),
     *  @SWG\Response(response=401,ref="#/responses/AuthorizationException"),
     *  @SWG\Response(response=403,ref="#responses/UnauthorizedException"),
     *  @SWG\Response(response=422,ref="#/responses/ResourceException"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function store(Plan $plan, NoteRequest $request)
    {
        // 存在时修改
        if ($note = $plan->notes()->where('user_id', auth()->id())->where('task_id', $request->task_id)->first()) {
            $this->authorize('isAuthor', $note);
            $note->fill($request->all());
            $note->save();
        }
        // 不存在则创建
        $this->authorize('isMember', $plan);

        $note = new Note($request->all());
        $note->course_id = $plan->course_id;
        $note->plan_id = $plan->id;
        $note->task_id = $request->task_id;
        $note->user_id = auth()->id();
        $note->save();

        return $this->response->item($note, new NoteTransformer())->setStatusCode(201);
    }

    /**
     * @SWG\Delete(
     *  path="/plans/{plan_id}/notes/{note_id}",
     *  tags={"web/note"},
     *  summary="删除",
     *  description="",
     *  @SWG\Parameter(name="plan_id",type="integer",in="path",required=true,description="版本ID"),
     *  @SWG\Parameter(name="note_id",type="integer",in="path",required=true,description="笔记ID"),
     *  @SWG\Response(response=204,description="ok"),
     *  @SWG\Response(response=401,ref="#/responses/AuthorizationException"),
     *  @SWG\Response(response=403,ref="#responses/UnauthorizedException"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function destroy(Plan $plan, $note)
    {
        $note = $plan->notes()->findOrFail($note);

        $this->authorize('isAuthor', $note);

        $note->delete();

        return $this->response->noContent();
    }
}
