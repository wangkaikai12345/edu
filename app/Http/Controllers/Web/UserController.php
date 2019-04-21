<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Transformers\MessageUserTransformer;
use App\Http\Transformers\UserTransformer;
use App\Models\User;

class UserController extends Controller
{
    /**
     * @SWG\Get(
     *  path="/users",
     *  tags={"web/her"},
     *  summary="用户",
     *  description="用于对用户进行搜索",
     *  @SWG\Parameter(name="user_id",in="query",type="integer",description="用户ID"),
     *  @SWG\Parameter(name="username",in="query",type="string",description="用户账户：右模糊:wang%；全模糊:%wang%"),
     *  @SWG\Response(response=200,ref="#/responses/UserResponse")
     * )
     */
    public function index()
    {
        $queryParams = request()->only(['username', 'email']);

        if (!$queryParams) {
            return $this->response->array(['data' => []]);
        }

        $users = User::filtered()->get();

        return $this->response->collection($users, new MessageUserTransformer());
    }

    /**
     * @SWG\Get(
     *  path="/users/{user_id}",
     *  tags={"web/her"},
     *  summary="她的信息",
     *  @SWG\Parameter(name="user_id",in="path",required=true,type="integer",description="用户ID"),
     *  @SWG\Parameter(name="include",in="query",type="string",description="是否包含关联数据：roles,profile"),
     *  @SWG\Response(response=200,description="",@SWG\Schema(ref="#/definitions/User"))
     * )
     */
    public function show($user)
    {
        $user = User::withCount(['fans', 'followers'])->findOrFail($user);

        return $this->response->item($user, new UserTransformer());
    }
}