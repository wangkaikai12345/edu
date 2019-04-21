<?php

namespace App\Http\Controllers\Backstage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FeedbackRequest;
use App\Http\Transformers\FeedbackTransformer;
use App\Models\Feedback;

class FeedbackController extends Controller
{
    public function index()
    {
        $feedbacks = Feedback::filtered()->sorted()->paginate(self::perPage());

        return view('admin.feebacks.index', compact('feedbacks'));
    }

    public function edit(Feedback $feedback)
    {
        return view('admin.feebacks.edit', compact('feedback'));
    }

    public function update(Feedback $feedback, FeedbackRequest $request)
    {
        $feedback->is_solved = $request->is_solved;
        $feedback->is_replied= $request->is_replied;
        $feedback->save();

        return $this->response->noContent();
    }
}
