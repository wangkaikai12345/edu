<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Status;
use App\Http\Transformers\ClassroomTransformer;
use App\Models\Classroom;
use App\Http\Controllers\Controller;

class ClassroomController extends Controller
{
    /**
     * @SWG\Get(
     *  path="/admin/classrooms",
     *  tags={"admin/classroom"},
     *  summary="班级列表",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(ref="#/parameters/ClassroomQuery-title"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomQuery-expiry_mode"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomQuery-expiry_started_at"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomQuery-expiry_ended_at"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomQuery-category_id"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomQuery-price"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomQuery-origin_price"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomQuery-created_at"),
     *  @SWG\Parameter(ref="#/parameters/Classroom-sort"),
     *  @SWG\Parameter(ref="#/parameters/Classroom-include"),
     *  @SWG\Parameter(ref="#/parameters/page"),
     *  @SWG\Parameter(ref="#/parameters/per_page"),
     *  @SWG\Response(response=200,ref="#/responses/ClassroomPagination"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function index(Classroom $classroom)
    {
        $data = Classroom::filtered()->sorted()->paginate(self::perPage());

        // 统计信息
        $stat = [];
        $stat['total'] = $classroom->count();
        $stat['published'] = $classroom->published()->count();
        $stat['closed'] = $classroom->closed()->count();
        $stat['draft'] = $classroom->draft()->count();

        return $this->response->paginator($data, new ClassroomTransformer())->setMeta($stat);
    }

    /**
     * @SWG\Patch(
     *  path="/admin/classrooms/{classroom_id}/publish",
     *  tags={"admin/classroom"},
     *  summary="发布/取消班级",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="classroom_id",in="path",type="integer",required=true,description="班级"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomForm-status"),
     *  @SWG\Response(response=201,ref="#/definitions/Classroom"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function publish(Classroom $classroom)
    {
        $classroom->status = request('status', 'published') === Status::PUBLISHED
            ? Status::PUBLISHED
            : Status::CLOSED;
        $classroom->save();

        return $this->response->item($classroom, new ClassroomTransformer());
    }

    /**
     * @SWG\Patch(
     *  path="/admin/classrooms/{classroom_id}/recommend",
     *  tags={"admin/classroom"},
     *  summary="推荐",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="classroom_id",in="path",type="integer",required=true,description="班级"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomForm-is_recommended"),
     *  @SWG\Parameter(ref="#/parameters/ClassroomForm-recommended_seq"),
     *  @SWG\Response(response=200,ref="#/definitions/Classroom"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function recommend(Classroom $classroom)
    {
        $classroom->is_recommended  = (boolean)request('is_recommended', true);
        $classroom->recommended_seq = (integer)request('recommended_seq', 0);
        $classroom->recommended_at = $classroom->is_recommended  ? now() : null;
        $classroom->save();

        return $this->response->item($classroom, new ClassroomTransformer());
    }
}
