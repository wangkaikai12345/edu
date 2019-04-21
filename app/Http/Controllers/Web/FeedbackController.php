<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\FeedbackRequest;
use App\Http\Transformers\FeedbackTransformer;
use App\Models\Feedback;

class FeedbackController extends Controller
{
    /**
     * @SWG\Post(
     *  path="/feedback",
     *  tags={"web/feedback"},
     *  summary="反馈",
     *  description="联系方式选填",
     *  @SWG\Parameter(ref="#/parameters/FeedbackForm-content"),
     *  @SWG\Parameter(ref="#/parameters/FeedbackForm-email"),
     *  @SWG\Parameter(ref="#/parameters/FeedbackForm-qq"),
     *  @SWG\Parameter(ref="#/parameters/FeedbackForm-wechat"),
     *  @SWG\Response(response=201,description="ok",ref="#/definitions/Feedback"),
     *  @SWG\Response(response=422,ref="#/responses/ResourceException"),
     * )
     */
    public function store(FeedbackRequest $request)
    {
        $feedback = Feedback::create($request->all());
        $feedback->user_id = auth()->id();
        $feedback->save();

        return $this->response->item($feedback, new FeedbackTransformer())->setStatusCode(201);
    }
}
