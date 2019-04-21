<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\ReviewRequest;
use App\Http\Transformers\ReviewTransformer;
use App\Models\Plan;
use App\Models\Review;

class ReviewController extends Controller
{
    /**
     * @SWG\Tag(name="web/review",description="版本评价")
     */

    /**
     * @SWG\Get(
     *  path="/plans/{plan_id}/reviews",
     *  tags={"web/review"},
     *  summary="列表",
     *  @SWG\Parameter(name="plan_id",in="path",type="integer"),
     *  @SWG\Parameter(ref="#/parameters/ReviewQuery-user_id"),
     *  @SWG\Parameter(ref="#/parameters/ReviewQuery-user:username"),
     *  @SWG\Parameter(ref="#/parameters/Review-sort"),
     *  @SWG\Parameter(ref="#/parameters/Review-include"),
     *  @SWG\Parameter(ref="#/parameters/page"),
     *  @SWG\Parameter(ref="#/parameters/per_page"),
     *  @SWG\Response(response=200,description="ok",@SWG\Schema(
     *      @SWG\Property(property="per_page",type="integer",minimum=1,example=1),
     *      @SWG\Property(property="current_page",type="integer",maximum=100,example=1),
     *      @SWG\Property(property="total",type="integer",example=1),
     *      @SWG\Property(property="my_review",type="object",description="我的评价信息",@SWG\Schema(ref="#/definitions/Review")),
     *  )),
     * )
     */
    public function index(Plan $plan)
    {
        $data = $plan->reviews()->filtered()->sorted()->paginate(self::perPage());

        // 获取当前用户对这个版本的评价
        if ($me = auth()->user()) {
            $myReview = $plan->reviews()->where('user_id', $me->id)->first();
        } else {
            $myReview = null;
        }

        return $this->response->paginator($data, new ReviewTransformer())->setMeta(['my_review' => $myReview]);
    }

    /**
     * @SWG\Get(
     *  path="/plans/{plan_id}/reviews/{review_id}",
     *  tags={"web/review"},
     *  summary="详情",
     *  @SWG\Parameter(name="plan_id",in="path",type="integer",required=true,description="版本ID"),
     *  @SWG\Parameter(name="review_id",in="path",type="integer",required=true,description="评价ID"),
     *  @SWG\Parameter(ref="#/parameters/Review-include"),
     *  @SWG\Response(response=200,description="ok",ref="#/definitions/Review")
     * )
     */
    public function show(Plan $plan, $review)
    {
        $item = $plan->reviews()->findOrFail($review);

        return $this->response->item($item, new ReviewTransformer());
    }

    /**
     * @SWG\Post(
     *  path="/plans/{plan_id}/reviews",
     *  tags={"web/review"},
     *  summary="添加/更新",
     *  description="用户只能对一个教学版本话进行一次评价。当不存在评价时，则创建，存在时即更新。",
     *  @SWG\Parameter(name="plan_id",in="path",type="integer",required=true,description="版本ID"),
     *  @SWG\Parameter(name="content",in="formData",type="string",maxLength=191,description="评价内容"),
     *  @SWG\Parameter(name="rating",in="formData",type="integer",minimum=0,maximum=5,description="评分"),
     *  @SWG\Response(response=201,description="ok",ref="#/definitions/Review"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function store(ReviewRequest $request, Plan $plan)
    {
        $me = auth()->user();

        // 判断该用户是否已评价该版本
        $review = $plan->reviews()->where('user_id', $me->id)->first();

        if (!$review) {
            $this->authorize('isMember', $plan);
            $review = new Review();
            $review->fill($request->all());
            $review->user_id   = $me->id;
            $review->course_id = $plan->course_id;
            $review->plan_id   = $plan->id;
            $review->save();
        } else {
            $this->authorize('isAuthor', $review);
            $review->fill($request->only(['content', 'rating']));
            $review->save();
            return $this->response->item($review, new ReviewTransformer());
        }

        return $this->response->item($review, new ReviewTransformer())->setStatusCode(201);
    }
}
