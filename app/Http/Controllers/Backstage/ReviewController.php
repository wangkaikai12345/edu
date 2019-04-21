<?php

namespace App\Http\Controllers\Backstage;

use App\Http\Controllers\Controller;
use App\Models\Review;

class ReviewController extends Controller
{

    public function index()
    {
        $reviews = Review::filtered()->sorted()->with(['course', 'user'])->paginate(self::perPage());

        return view('admin.reviews.index', compact('reviews'));
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return $this->response->noContent();
    }
}
