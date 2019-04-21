<?php
/**
 * Created by PhpStorm.
 * User: wangbaolong
 * Date: 2018/6/15
 * Time: 15:13
 */

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\FollowRequest;
use App\Http\Transformers\FollowTransformer;
use App\Models\Follow;

class FollowController extends Controller
{
    /**
     * @SWG\Tag(name="web/follow",description="关注")
     */

    /**
     * @SWG\Get(
     *  path="/my-fans",
     *  tags={"web/follow"},
     *  summary="粉丝列表",
     *  description="我的粉丝",
     *  @SWG\Parameter(ref="#/parameters/FollowQuery-user_id"),
     *  @SWG\Parameter(ref="#/parameters/FollowQuery-user:username"),
     *  @SWG\Parameter(ref="#/parameters/FollowQuery-is_pair"),
     *  @SWG\Parameter(ref="#/parameters/Follow-sort"),
     *  @SWG\Parameter(ref="#/parameters/Follow-include"),
     *  @SWG\Parameter(ref="#/parameters/page"),
     *  @SWG\Parameter(ref="#/parameters/per_page"),
     *  @SWG\Response(response=200,ref="#/responses/FollowPagination"),
     *  @SWG\Response(response=401,ref="#/responses/AuthorizationException"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function fans()
    {
        $fans = Follow::where('follow_id', auth()->id())->filtered()->sorted()->paginate(self::perPage());

        return $this->response->paginator($fans, (new FollowTransformer())->setDefaultIncludes(['user']));
    }

    /**
     * @SWG\Get(
     *  path="/my-followers",
     *  tags={"web/follow"},
     *  summary="我的关注",
     *  @SWG\Parameter(ref="#/parameters/FollowQuery-follow_id"),
     *  @SWG\Parameter(ref="#/parameters/FollowQuery-follow:username"),
     *  @SWG\Parameter(ref="#/parameters/FollowQuery-is_pair"),
     *  @SWG\Parameter(ref="#/parameters/Follow-sort"),
     *  @SWG\Parameter(ref="#/parameters/Follow-include"),
     *  @SWG\Parameter(ref="#/parameters/page"),
     *  @SWG\Parameter(ref="#/parameters/per_page"),
     *  @SWG\Response(response=200,ref="#/responses/FollowPagination"),
     *  @SWG\Response(response=401,ref="#/responses/AuthorizationException"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function followers()
    {
        $followers = Follow::where('user_id', auth()->id())->filtered()->sorted()->paginate(self::perPage());

        return $this->response->paginator($followers, (new FollowTransformer())->setDefaultIncludes(['follow']));
    }

    /**
     * @SWG\Post(
     *  path="/my-followers",
     *  tags={"web/follow"},
     *  summary="关注/取关",
     *  description="当未关注时，进行关注；否则取关。",
     *  @SWG\Parameter(ref="#/parameters/FollowForm-follow_id"),
     *  @SWG\Response(response=201,description="ok",ref="#/definitions/Follow"),
     *  @SWG\Response(response=401,ref="#/responses/AuthorizationException"),
     *  @SWG\Response(response=403,ref="#responses/UnauthorizedException"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function store(FollowRequest $request)
    {
        if ($request->follow_id == auth()->id()) {
            $this->response->errorForbidden(__('Operation is not supported.'));
        }

        if (!$item = Follow::where('user_id', auth()->id())->where('follow_id', $request->follow_id)->first()) {
            $item = new Follow($request->all());
            $item->user_id = auth()->id();
            $item->save();
            return $this->response->item($item, (new FollowTransformer())->setDefaultIncludes(['follow']))->setStatusCode(201);
        } else {
            $item->delete();
            return $this->response->noContent();
        }
    }
}