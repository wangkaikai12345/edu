<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Transformers\NoticeTransformer;
use App\Models\Notice;
use Dingo\Api\Http\Request;

class NoticeController extends Controller
{
    /**
     * @SWG\Tag(name="web/notice",description="官网公告/版本公告")
     */

    /**
     * @SWG\Get(
     *  path="/notices",
     *  tags={"web/notice"},
     *  summary="官网公告分页列表",
     *  description="",
     *  @SWG\Parameter(name="is_seen",type="boolean",in="query",description="是否仅展示当前用户能够看到的公告",default=false),
     *  @SWG\Parameter(ref="#/parameters/NoticeQuery-started_at"),
     *  @SWG\Parameter(ref="#/parameters/NoticeQuery-ended_at"),
     *  @SWG\Parameter(ref="#/parameters/Notice-sort"),
     *  @SWG\Parameter(ref="#/parameters/Notice-include"),
     *  @SWG\Parameter(ref="#/parameters/page"),
     *  @SWG\Parameter(ref="#/parameters/per_page"),
     *  @SWG\Response(response=200,ref="#/responses/NoticePagination")
     * )
     */
    public function index(Request $request, Notice $notice)
    {
        if ($request->is_seen) {
            $notices = $notice->web()->onShow()->paginate(self::perPage());
        } else {
            $notices = $notice->web()->paginate(self::perPage());
        }

        return $this->response->paginator($notices, new NoticeTransformer());
    }
}