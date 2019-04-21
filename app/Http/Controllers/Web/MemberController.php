<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Transformers\PlanMemberTransformer;
use App\Models\Plan;

class MemberController extends Controller
{
    /**
     * @SWG\Tag(name="web/member",description="版本成员")
     */

    /**
     * @SWG\Get(
     *  path="/plans/{plan_id}/members",
     *  tags={"web/member"},
     *  summary="版本成员列表（普通用户）",
     *  description="",
     *  @SWG\Parameter(name="plan_id",type="integer",in="path",required=true,description="版本ID"),
     *  @SWG\Parameter(ref="#/parameters/page"),
     *  @SWG\Parameter(ref="#/parameters/per_page"),
     *  @SWG\Parameter(ref="#/parameters/PlanMemberQuery-user_id"),
     *  @SWG\Parameter(ref="#/parameters/PlanMemberQuery-user:username"),
     *  @SWG\Parameter(ref="#/parameters/PlanMemberQuery-order_id"),
     *  @SWG\Parameter(ref="#/parameters/PlanMemberQuery-order:title"),
     *  @SWG\Parameter(ref="#/parameters/PlanMemberQuery-join_type"),
     *  @SWG\Parameter(ref="#/parameters/PlanMemberQuery-deadline"),
     *  @SWG\Parameter(ref="#/parameters/PlanMemberQuery-remark"),
     *  @SWG\Parameter(ref="#/parameters/PlanMemberQuery-learned_count"),
     *  @SWG\Parameter(ref="#/parameters/PlanMemberQuery-learned_compulsory_count"),
     *  @SWG\Parameter(ref="#/parameters/PlanMemberQuery-notes_count"),
     *  @SWG\Parameter(ref="#/parameters/PlanMemberQuery-is_finished"),
     *  @SWG\Parameter(ref="#/parameters/PlanMemberQuery-finished_at"),
     *  @SWG\Parameter(ref="#/parameters/PlanMemberQuery-status"),
     *  @SWG\Parameter(ref="#/parameters/PlanMemberQuery-last_learned_at"),
     *  @SWG\Parameter(ref="#/parameters/PlanMember-sort"),
     *  @SWG\Parameter(ref="#/parameters/PlanMember-include"),
     *  @SWG\Parameter(ref="#/parameters/page"),
     *  @SWG\Parameter(ref="#/parameters/per_page"),
     *  @SWG\Response(response=200,ref="#/responses/PlanMemberPagination"),
     * )
     */
    public function index(Plan $plan)
    {
        $data = $plan->members()->sorted()->paginate(self::perPage());

        return $this->response->paginator($data, new PlanMemberTransformer());
    }
}
