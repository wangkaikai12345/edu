<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Transformers\NoteTransformer;
use App\Models\Note;

class NoteController extends Controller
{
    /**
     * @SWG\Get(
     *  path="/admin/notes",
     *  tags={"admin/note"},
     *  summary="列表",
     *  description="",
     *  @SWG\Parameter(ref="#/parameters/NoteQuery-course_id"),
     *  @SWG\Parameter(ref="#/parameters/NoteQuery-course:title"),
     *  @SWG\Parameter(ref="#/parameters/NoteQuery-plan_id"),
     *  @SWG\Parameter(ref="#/parameters/NoteQuery-plan:title"),
     *  @SWG\Parameter(ref="#/parameters/NoteQuery-task_id"),
     *  @SWG\Parameter(ref="#/parameters/NoteQuery-task:title"),
     *  @SWG\Parameter(ref="#/parameters/NoteQuery-user_id"),
     *  @SWG\Parameter(ref="#/parameters/NoteQuery-user:username"),
     *  @SWG\Parameter(ref="#/parameters/NoteQuery-is_public"),
     *  @SWG\Parameter(ref="#/parameters/Note-sort"),
     *  @SWG\Parameter(ref="#/parameters/Note-include"),
     *  @SWG\Parameter(ref="#/parameters/page"),
     *  @SWG\Parameter(ref="#/parameters/per_page"),
     *  @SWG\Response(response=200,ref="#/responses/NotePagination"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function index()
    {
        $data = Note::filtered()->sorted()->paginate(self::perPage());

        return $this->response->paginator($data, new NoteTransformer());
    }

    /**
     * @SWG\Delete(
     *  path="/admin/notes/{note_id}",
     *  tags={"admin/note"},
     *  summary="删除）",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(in="path",name="note_id",type="integer",required=true,description="笔记ID"),
     *  @SWG\Response(response=204,description="ok"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function destroy(Note $note)
    {
        $note->delete();

        return $this->response->noContent();
    }
}
