<?php

namespace App\Http\Controllers\Web;

use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Http\Transformers\UserTransformer;
use App\Models\User;

class TeacherController extends Controller
{
    /**
     * @SWG\Tag(name="web/teacher",description="教师相关")
     */

    /**
     * @SWG\Get(
     *  path="/teachers",
     *  tags={"web/teacher"},
     *  summary="列表",
     *  description="",
     *  @SWG\Parameter(ref="#/parameters/page"),
     *  @SWG\Parameter(ref="#/parameters/per_page"),
     *  @SWG\Parameter(name="recommended_seq",type="string",in="query",description="推荐教师列表：!0；非推荐教师列表：0;"),
     *  @SWG\Parameter(ref="#/parameters/sort"),
     *  @SWG\Response(response=200,ref="#/responses/UserPagination")
     * )
     */
    public function index()
    {
        $data = User::role(UserType::TEACHER)->filtered()->sorted()->paginate(self::perPage());

        return $this->response->paginator($data, new UserTransformer());
    }

    /**
     * @SWG\Get(
     *  path="/teachers/list",
     *  tags={"web/teacher"},
     *  summary="简易列表",
     *  description="仅包含用户名、昵称、手机",
     *  @SWG\Response(response=200,description="成功",@SWG\Schema(
     *     @SWG\Property(property="id",type="integer"),
     *     @SWG\Property(property="username",type="string"),
     *     @SWG\Property(property="phone",type="string"),
     *  ))
     * )
     */
    public function list()
    {
        $data = User::role(UserType::TEACHER)->filtered()->get(['id', 'username', 'phone']);

        return $this->response->array(compact('data'));
    }
}