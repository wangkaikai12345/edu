<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Transformers\ChapterTransformer;
use App\Models\Plan;

class ChapterController extends Controller
{
    /**
     * @SWG\Tag(name="web/chapter",description="章节")
     */

    /**
     * @SWG\Get(
     *  path="/plans/{plan_id}/chapters",
     *  tags={"web/chapter"},
     *  summary="章节列表（普通用户）",
     *  description="",
     *  @SWG\Parameter(name="plan_id",in="path",required=true,type="integer",description="版本ID"),
     *  @SWG\Parameter(ref="#/parameters/Chapter-include"),
     *  @SWG\Response(response=200,ref="#/responses/ChapterResponse"),
     * )
     */
    public function index(Plan $plan)
    {
        request()->offsetSet('include', 'children.tasks:status(published)');

        $data = $plan->chapters()->orderBy('seq')->where(['parent_id' => 0])->get();

        return $this->response->collection($data, new ChapterTransformer());
    }
}
