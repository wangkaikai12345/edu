<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Transformers\LogTransformer;
use App\Models\Log;

class LogController extends Controller
{
    // 标签
    /**
     * @SWG\Tag(name="admin/log",description="后台日志相关接口")
     */

    /**
     * @SWG\Get(
     *  path="/admin/logs",
     *  tags={"admin/log"},
     *  summary="登录日志",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(ref="#/parameters/page"),
     *  @SWG\Parameter(ref="#/parameters/per_page"),
     *  @SWG\Parameter(name="type",type="string",in="query",enum={"all","login"},description="all全部日志、login登陆日志"),
     *  @SWG\Parameter(ref="#/parameters/LogQuery-user_id"),
     *  @SWG\Parameter(ref="#/parameters/LogQuery-user:username"),
     *  @SWG\Parameter(ref="#/parameters/LogQuery-request_time"),
     *  @SWG\Parameter(ref="#/parameters/LogQuery-response_time"),
     *  @SWG\Parameter(ref="#/parameters/Log-sort"),
     *  @SWG\Parameter(ref="#/parameters/Log-include"),
     *  @SWG\Response(response=200,description="ok",ref="#/responses/LogPagination"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function index(Log $log)
    {
        $type = request('type', 'all');

        if ($type === 'login') {
            $data = $log->filtered()->sorted()->where(['method' => 'POST', 'url' => dingo_route('login.store'), 'status_code' => '200'])->paginate(self::perPage());
        } else {
            $data = $log->filtered()->sorted()->paginate(self::perPage());
        }

        return $this->response->paginator($data, new LogTransformer());
    }
}