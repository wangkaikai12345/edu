<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FeedbackRequest;
use App\Http\Transformers\FeedbackTransformer;
use App\Models\Feedback;

class FeedbackController extends Controller
{
    /**
     * @SWG\Get(
     *  path="/admin/feedback",
     *  tags={"admin/feedback"},
     *  summary="列表",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(ref="#/parameters/FeedbackQuery-email"),
     *  @SWG\Parameter(ref="#/parameters/FeedbackQuery-wechat"),
     *  @SWG\Parameter(ref="#/parameters/FeedbackQuery-qq"),
     *  @SWG\Parameter(ref="#/parameters/FeedbackQuery-is_solved"),
     *  @SWG\Parameter(ref="#/parameters/FeedbackQuery-is_replied"),
     *  @SWG\Parameter(ref="#/parameters/Feedback-sort"),
     *  @SWG\Parameter(ref="#/parameters/page"),
     *  @SWG\Parameter(ref="#/parameters/per_page"),
     *  @SWG\Response(response=200,description="ok",ref="#/responses/FeedbackPagination"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function index()
    {
        $data = Feedback::filtered()->sorted()->paginate(self::perPage());

        return $this->response->paginator($data, new FeedbackTransformer());
    }

    /**
     * @SWG\Put(
     *  path="/admin/feedback/{feedback_id}",
     *  tags={"admin/feedback"},
     *  summary="更新状态",
     *  description="用于更新是否已解决、用于是否已回复",
     *  produces={"application/json"},
     *  @SWG\Parameter(in="path",name="feedback_id",type="integer",required=true,description="反馈ID"),
     *  @SWG\Parameter(ref="#/parameters/FeedbackForm-is_solved"),
     *  @SWG\Parameter(ref="#/parameters/FeedbackForm-is_replied"),
     *  @SWG\Response(response=204,description="ok"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function update(Feedback $feedback ,FeedbackRequest $request)
    {
        $feedback->fill($request->only(['is_solved', 'is_replied']));
        $feedback->save();

        return $this->response->noContent();
    }
}
