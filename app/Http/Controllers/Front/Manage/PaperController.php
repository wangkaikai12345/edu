<?php

namespace App\Http\Controllers\Front\Manage;

use App\Http\Requests\Front\PaperRequest;
use App\Models\ModelHasTag;
use App\Models\Paper;
use App\Models\PaperQuestion;
use App\Models\Tag;
use App\Models\TagGroup;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class PaperController extends Controller
{
    /**
     * 试卷列表页
     *
     * @author Luwnto
     */
    public function index(Request $request, Paper $paper)
    {
        $title = $request->title;
        $username = $request->username;
        $tag = $request->tag;
        $papers = $paper
            ->where(function ($query) use ($username) {
                if (!empty($username)) {
                    $user = User::where('username', $username)->first();
                    $uid = !empty($user) ? $user->id : null;
                    return $query ->where('user_id', $uid);
                }
            })
            ->when($title, function ($query) use ($title) {
                return $query->where('title', 'like', '%' . $title . '%');
            })
            ->when($tag, function ($query) use ($tag) {
                $tids = Tag::where('name', 'like', "%{$tag}%")->pluck('id');
                $mids = ModelHasTag::where('model_type', 'paper')
                    ->whereIn('tag_id', $tids)
                    ->pluck('model_id');
                return $query->whereIn('id', $mids);
            })
            ->where('type', 'test')
            ->with(['user' => function ($query) {
                $query->select('id', 'username');
            }, 'tags' => function ($query) {
                $query->select('id', 'name');
            }])
            ->orderBy('id', 'DESC')
            ->paginate(config('theme.teacher_num'));
        return view('teacher.exam.paper', compact('papers'));
    }

    /**
     * 添加试卷的页面
     *
     * @author Luwnto
     */
    public function create()
    {
        $labels = Tag::select('id', 'name as text')
            ->where(function ($q){
                return $q->where('tag_group_id', TagGroup::where('name', 'course')->first()->id);
            })->get()->toArray();

        $labels = json_encode($labels);
        return view('teacher.exam.insert_paper', compact('labels'));
    }

    /**
     * 保存添加的试卷
     *
     * @author Luwnto
     */
    public function store(PaperRequest $request, Paper $paper, PaperQuestion $paperQuestion)
    {
        try{
            DB::beginTransaction();
            // 处理分钟成秒
            $request->offsetSet('expect_time', $request->expect_time * 60);
            // 生成试卷记录
            $paper->fill(array_only($request->all(), ['title', 'expect_time', 'total_score', 'pass_score', 'extra', 'questions_count']));
            $paper->user_id = auth('web')->id() ?? 1;
            $paper->save();

            // 生成试卷和题目的关联表
            // 处理题目和分数设置
            $insertData = [];
            foreach ($request->question_ids as $qid) {
                $insertData[] = [
                    'paper_id' => $paper->id,
                    'question_id' =>$qid,
                    'score' => empty($request['question_score_' . $qid]) ? 0 : $request['question_score_' . $qid],
                ];
            }
            $paperQuestion->newQuery()->insert($insertData);

            // 如果有标签, 添加标签关联
            $tags = $request->input('tags');
            $paper->tags()->sync($tags);
            DB::commit();
        } catch (\Exception $e) {
            \Log::info($e);
            DB::rollback();
            return ajax('400', '添加试卷失败!');
        }
        return ajax('200', '添加试卷成功!');
    }

    /**
     * 一个试卷的详情
     *
     * @author Luwnto
     */
    public function show(Paper $paper)
    {
        $paper->qs = $paper->paperQuestions()->with('question')->get();
        return view('teacher.exam.paper_preview', compact('paper'));
    }

    /**
     * 编辑试卷的页面
     *
     * @author Luwnto
     */
    public function edit(Paper $paper)
    {
        dd('编辑视频的页面', $paper);
    }

    /**
     * 更新视频
     *
     * @author Luwnto
     */
    public function update(PaperRequest $request, $id)
    {
        dd('更新视频成功');
    }

    /**
     * 删除试卷
     */
    public function destroy($id)
    {
        dd('删除试卷成功');
    }
}
