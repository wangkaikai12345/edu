<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Transformers\NoticeTransformer;
use App\Models\Plan;

class PlanNoticeController extends Controller
{
    /**
     * @SWG\Get(
     *  path="/plans/{plan_id}/notices",
     *  tags={"web/notice"},
     *  summary="版本公告列表（普通用户）",
     *  description="",
     *  @SWG\Parameter(name="plan_id",type="integer",in="path",required=true,description="版本ID"),
     *  @SWG\Parameter(name="is_seen",type="boolean",in="query",description="是否仅展示当前用户能够看到的公告",default=false),
     *  @SWG\Parameter(ref="#/parameters/page"),
     *  @SWG\Parameter(ref="#/parameters/per_page"),
     *  @SWG\Parameter(ref="#/parameters/Notice-sort"),
     *  @SWG\Parameter(ref="#/parameters/Notice-include"),
     *  @SWG\Response(response=200,ref="#/responses/NoticePagination"),
     * )
     */
    public function index(Plan $plan)
    {
        $data = $plan->notices()->when(request('is_seen'), function ($query) {
            return $query->where('started_at', '<=', now())->where('ended_at', '>=', now());
        })->sorted()->paginate(self::perPage());

        return $this->response->paginator($data, new NoticeTransformer());
    }
}
