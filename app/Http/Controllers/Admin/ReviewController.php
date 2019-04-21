<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Transformers\ReviewTransformer;
use App\Models\Review;

class ReviewController extends Controller
{
    /**
     * @SWG\Tag(name="admin/review",description="评价")
     */

    /**
     * @SWG\Get(
     *  path="/admin/review",
     *  tags={"admin/review"},
     *  summary="列表",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(ref="#/parameters/ReviewQuery-course_id"),
     *  @SWG\Parameter(ref="#/parameters/ReviewQuery-course:title"),
     *  @SWG\Parameter(ref="#/parameters/ReviewQuery-plan_id"),
     *  @SWG\Parameter(ref="#/parameters/ReviewQuery-plan:title"),
     *  @SWG\Parameter(ref="#/parameters/ReviewQuery-user_id"),
     *  @SWG\Parameter(ref="#/parameters/ReviewQuery-user:username"),
     *  @SWG\Parameter(ref="#/parameters/Review-sort"),
     *  @SWG\Parameter(ref="#/parameters/Review-include"),
     *  @SWG\Parameter(ref="#/parameters/page"),
     *  @SWG\Parameter(ref="#/parameters/per_page"),
     *  @SWG\Response(response=200,ref="#/responses/TopicPagination"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function index()
    {
        $data = Review::filtered()->sorted()->paginate(self::perPage());

        return $this->response->paginator($data, new ReviewTransformer());
    }

    /**
     * @SWG\Get(
     *  path="/admin/reviews/{review_id}",
     *  tags={"admin/review"},
     *  summary="详情",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="plan_id",in="path",type="integer",required=true,description="版本"),
     *  @SWG\Parameter(name="review_id",in="path",type="integer",required=true,description="评价ID"),
     *  @SWG\Response(response=204,description="ok"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function destroy(Review $review)
    {
        $review->delete();

        return $this->response->noContent();
    }
}
