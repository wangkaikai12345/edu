<?php

namespace App\Http\Controllers\Web;

use App\Http\Transformers\ClassroomTransformer;
use App\Models\Classroom;
use App\Http\Controllers\Controller;

class ClassroomController extends Controller
{
    /**
     * @SWG\Get(
     *  path="/classrooms",
     *  tags={"web/classroom"},
     *  summary="班级列表（普通用户）",
     *  description="仅展示已发布班级",
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
     *  @SWG\Response(response=200,description="ok",ref="#/responses/ClassroomPagination"),
     * )
     */
    public function index()
    {
        $data = Classroom::published()->filtered()->sorted()->paginate(self::perPage());

        return $this->response->paginator($data, new ClassroomTransformer());
    }

    /**
     * @SWG\Get(
     *  path="/classrooms/{classroom_id}",
     *  tags={"web/classroom"},
     *  summary="班级详情（普通用户）",
     *  description="",
     *  @SWG\Parameter(name="classroom_id",in="path",type="integer",required=true,description="班级ID"),
     *  @SWG\Parameter(ref="#/parameters/Classroom-include"),
     *  @SWG\Response(response=200,description="ok",ref="#/definitions/Classroom"),
     * )
     */
    public function show($classroom)
    {
        $classroom = Classroom::published()->findOrFail($classroom);

        return $this->response->item($classroom, new ClassroomTransformer());
    }
}
