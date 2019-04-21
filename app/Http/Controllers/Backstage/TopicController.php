<?php

namespace App\Http\Controllers\Backstage;

use App\Enums\TopicStatus;
use App\Http\Controllers\Controller;
use App\Http\Transformers\TopicTransformer;
use App\Models\Topic;
use App\Rules\CustomEnumRule;
use Illuminate\Http\Request;

class TopicController extends Controller
{

    /**
     * 问答管理
     *
     * @param Topic $topic
     * @return \Dingo\Api\Http\Response
     */
    public function index(Topic $topic)
    {
        request()->offsetSet('type', 'question');

        $topics = $topic->filtered()->sorted()
            ->with(['course', 'user'])
            ->paginate(self::perPage());


        return view('admin.topics.index', compact('topics'));
    }


    public function update(Request $request, Topic $topic)
    {
        $this->validate($request, [
            'status' => ['sometimes', new CustomEnumRule(TopicStatus::class)],
            'is_stick' => ['sometimes', 'boolean'],
            'is_elite' => ['sometimes', 'boolean'],
        ]);

        $request->has('status') && $topic->status = $request->status;
        $request->has('is_stick') && $topic->is_stick = (bool)$request->is_stick;
        $request->has('is_elite') && $topic->is_elite = (bool)$request->is_elite;
        $topic->save();

        return $this->response->noContent();
    }

    public function destroy(Topic $topic)
    {
        $topic->delete();

        return $this->response->noContent();
    }
}
