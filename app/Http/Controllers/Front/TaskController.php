<?php

namespace App\Http\Controllers\Front;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\Front\FrontTaskRequest;
use App\Http\Requests\Front\ReplyRequest;
use App\Http\Requests\Front\TopicRequest;
use App\Models\Chapter;
use App\Models\Classroom;
use App\Models\ClassroomCourse;
use App\Models\ClassroomMember;
use App\Models\ClassroomResult;
use App\Models\Note;
use App\Models\PaperResult;
use App\Models\Plan;
use App\Models\Reply;
use App\Models\Task;
use App\Models\TaskResult;
use App\Models\Topic;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * 任务学习
     *
     * @param Task $task
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author 王凯
     */
    public function show(Chapter $chapter, FrontTaskRequest $request)
    {
        // 第一部分--查看任务是否合法
        $plan = Plan::findOrFail($chapter->plan_id);

        $me = auth('web')->user();

//        // 判断是班级查看还是普通课程查看
//        if ($cid = $request->cid) {
//            $member = ClassroomMember::where(['user_id' => $me->id, 'classroom_id' => $cid])->first();
//        } else {
//            $member = $plan->isMember();
//        }
//
//       !$member && abort(404, '您还没加入学习');
//
//        // 版本成员
//        if ($member && empty($member->classroom_id)) {
//            $plan->status !== Status::PUBLISHED && abort(404, '学习版本不存在');
//        }

        $member = '';

        // 是否是版本的教师
        $control = $plan->isControl();

        // 初始化任务信息
        $task = '';

        // 如果没有查看具体任务或者查看了但是任务不是免费的
//        if (!$task ||  $task && !$task->is_free) {
//
//            if (!$member && !$control) abort(404, '您还未加入版本');
//        }

        // 验证查看的任务是否合法
        if ($task_id = $request->task) {

            $task = $chapter->tasks()->findOrFail($task_id);

            if (!$task) abort(404);
        }

        // 如果是解锁
        if ($plan->learn_mode == 'lock') {

            // 如果是移动端，直接返回
            if (is_show_mobile_page()) {
                return back()->with('error', '解锁课程移动端不支持学习');
            }

            // 任务类型分组
            $all = $chapter->tasks->where('status', 'published')->sortBy('type')->groupBy('type');

            $tasks = [];
            //
            foreach ($all as $type => $taskArray) {

                foreach ($taskArray as $value) {

                    if ($type == 'c-task') {
                        $tasks[$type][] = $value;
                        continue;
                    }

                    if ($type == 'f-extra') {

                        // 如果是扩展并且作业的未提交
                        if (!homeworkResult($chapter->id, auth('web')->id())) {
                            break 2;
                        }

                        $tasks[$type][] = $value;
                        continue;
                    }

                    if ($type == 'd-homework') {

                        // 如果是作业并且任务的主体还都没完成
                        if (typeResult($chapter->id, 'c-task', auth('web')->id()) != 100) {
                            break 2;
                        }

                        $tasks[$type][] = $value;
                        continue;
                    }

                    $res = $value->currentResult(auth('web')->id());

                    if ($res == 'finish') {
                        $tasks[$type][] = $value;

                    } else {

                        $tasks[$type][] = $value;
                        break 2;
                    }

                }
            }

            $types = array_keys($tasks);

            // 当前的任务类型
            $type = $request->type ? $request->type : ($types[0] ?? '');

        } else {

            // 整理章节下的类型
            $types = ['c-task'];

            // 任务类型分组
            $taskss = $chapter->tasks->where('status', 'published');

            $tasks['c-task'] = $taskss;

            // 当前的任务类型
            $type = 'c-task';

        }

        if (!$tasks) {
            return back()->with('error', '还没有任何任务');
        }

        // 当前的任务
        $task = $task ? $task : ($tasks[$type][0] ?? '');

        if (!$task) {
            return back()->with('error', '任务还未开启');
        }

        $summary = [];

        // 总结数据

        // 如果是查看总结
        if ($request->summary &&
            $request->summary == 'summary' && typeResult($chapter->id, 'd-homework', auth('web')->id()) == 100 &&
            !empty($member->classroom_id)
        ) {

            // 综合成绩
            $classroom = Classroom::find($member->classroom_id);

            // 只获取关
            $chaps = $classroom->chaps();

            // 最近7关的成绩
            $score = [0, 0, 0, 0, 0, 0, 0];

            $index = $chaps->search($chapter);

            // 如果通过的关数达到七关
            foreach ($score as $k => $value) {
                if ($index >= 6) {
                    $ch = isset($chaps[$index - 6 + $k]) ? $chaps[$index - 6 + $k]->classroomResult($classroom->id) : 0;
                    $score[$k] = $ch ? $ch->score : 0;
                } else {
                    // 没有达到七关
                    $ch = isset($chaps[$k]) ? $chaps[$k]->classroomResult($classroom->id) : 0;
                    $score[$k] = $ch ? $ch->score : 0;
                }
            }

            // 最近七关的成绩
            $summary['seven'] = $score;

            $classroomResult = $chapter->classroomResult($classroom->id);
            // 我的成绩
            $summary['mine'] = $classroomResult;

            // 成绩排名前三名
            $mark = ClassroomResult::where([
                'classroom_id' => $classroom->id,
                'chapter_id' => $chapter->id,
            ])->with('user')->latest('score')->get();

            $myMark = $classroomResult ? $mark->pluck('id')->search($classroomResult->id) + 1 : 1;

            // 排名
            $summary['mark'] = $mark;
            $summary['myMark'] = $myMark;

            $summary['ratio'] = ytof($myMark/$mark->count());

            // 作业成绩 1.查询关下的所有作业
            $homeworks = $chapter->tasks()
                ->where('type', 'd-homework')
                ->where('status', 'published')
                ->with(['target' => function($query){
                    $query->with(['homeworkPosts' => function($query){
                        $query->where(['user_id' => auth('web')->id(), 'locked' => 'open'])->latest();
                    }]);
                }])
                ->get();

            $summary['homeworks'] = $homeworks;

            // 查询笔记数和问答数
            $taskIds = $chapter->tasks->pluck('id');

            $summary['notes'] = Note::where('user_id', auth('web')->id())->whereIn('task_id', $taskIds)->count();
            $summary['topics'] = Topic::where('user_id', auth('web')->id())->whereIn('task_id', $taskIds)->count();

        }

        // 如果是版本切换
        if ($request->ajax()) {

            return view('frontend.review.task.'.$task->target_type, compact('task', 'member', 'chapter'));
        }

        return frontend_view('task.layout', compact('task', 'tasks', 'types', 'chapter', 'member', 'summary'));

    }

    /**
     * 考试页面
     *
     * @param Task $task
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author 王凯
     */
    public function test(Task $task)
    {
        // 验证
        $plan = Plan::findOrFail($task->plan_id);

        // 版本信息
        if ($plan->status !== Status::PUBLISHED) abort(404);

        if (!$task->is_free) {
            // 加入信息
            $plan_member = $plan->members->where('user_id', auth('web')->id())->first();

            // 是否是版本的教师
            $control = $plan->isControl(auth('web')->id());

            if (!$plan_member && !$control) abort(404);
        }

        if ($task['target_type'] != 'test') abort(404);

        // 考试信息
        $test = $task->target;

        // 考试题目
        $questions = $task->target->questions;

        // 考试结果
        $results = $task->target->results->where('user_id', auth('web')->id());

        return frontend_view('task.test_content', compact('test', 'questions', 'results'));
    }

    /**
     * 任务添加问答
     *
     * @param TopicRequest $request
     * @param Task $task
     * @return \Illuminate\Http\JsonResponse
     * @author 王凯
     */
    public function storeTopic(TopicRequest $request, Task $task)
    {
        // 数据验证，是否是版本的成员
        if (!$plan = $task->plan) return ajax('400', '版本任务不存在');

//        if (!$plan->isMember()) return ajax('400', '您还未加入课程版本');

        $topic = new Topic($request->all());
        $topic->user_id = auth('web')->id();
        $topic->course_id = $plan->course_id;
        $topic->plan_id = $plan->id;
        $topic->task_id = $task->id;
        $topic->save();

        return ajax('200', '问答操作成功', $topic);
    }

    /**
     * 获取版本下的问答
     *
     * @param Request $request
     * @param Task $task
     * @return \Illuminate\Http\JsonResponse
     * @author 王凯
     */
    public function getTopic(Request $request, Task $task)
    {
        // 数据验证，是否是版本的成员
        if (!$plan = $task->plan) return ajax('400', '版本任务不存在');

//        if (!$plan->isMember()) return ajax('400', '您还未加入课程版本');

        $topics = $task->topics()->where('type', 'question')->orderBy('hit_count', 'desc')->paginate(5);

        // 我提问的
        if ($request->type == 'question') {
            $topics = $task->topics()->where(['type' => 'question', 'user_id' => auth('web')->id()])->paginate(5);
        }

        // 我回答的
        if ($request->type == 'reply') {
            $topics = $task->replies()->with('topic')->where('user_id', auth('web')->id())->paginate(5);
        }

        return ajax('200', '问答操作成功', $topics);
    }

    /**
     * 版本下的问答搜索
     *
     * @param Request $request
     * @param Task $task
     * @return \Illuminate\Http\JsonResponse
     * @author 王凯
     */
    public function searchTopic(Request $request, Task $task)
    {
        $key = $request->key;

        $type = $request->type;

        // 数据验证，是否是版本的成员
        if (!$plan = $task->plan) return ajax('400', '版本任务不存在');

//        if (!$plan->isMember()) return ajax('400', '您还未加入课程版本');

        $topics = $task->topics()
            ->when($key, function ($query) use ($key) {
                return $query->where('title', 'like', '%' . $key . '%');
            })
            ->when($type, function ($query) use ($type, $task) {

                if ($type == 'question') {
                    return $query->where('user_id', auth('web')->id());
                }
                if ($type == 'reply') {

                    $ids = $task->replies()->where('user_id', auth('web')->id())->pluck('topic_id');
                    return $query->whereIn('id', $ids);
                }
            })
            ->where('type', 'question')
            ->get();

        return ajax('200', '问答搜索成功', $topics);
    }

    /**
     * 获取问答和回复列表
     *
     * @param Task $task
     * @param Topic $topic
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author 王凯
     */
    public function getReply(Task $task, Topic $topic)
    {
        if ($task->id != $topic->task->id) return ajax('400', '问答不存在');

        $reply = $topic->replies()->with('user')->get()->toArray();

        foreach ($reply as &$value) {

            $value['user']['avatar']  = render_cover($value['user']['avatar'] , 'avatar');
        }

        return ajax('200', '回复获取成功', $reply);
    }

    /**
     * 问答添加回复
     *
     * @param ReplyRequest $request
     * @param Task $task
     * @param Topic $topic
     * @return \Illuminate\Http\JsonResponse
     * @author 王凯
     */
    public function storeReply(ReplyRequest $request, Task $task, Topic $topic)
    {
        // 数据验证，是否是版本的成员
        if (!$plan = $task->plan) return ajax('400', '版本任务不存在');

//        if (!$plan->isMember()) return ajax('400', '您还未加入课程版本');

        $reply = new Reply($request->all());
        $reply->course_id = $topic->course_id;
        $reply->plan_id = $topic->plan_id;
        $reply->topic_id = $topic->id;
        $reply->task_id = $task->id;
        $reply->user_id = auth('web')->id();
        $reply->save();

        $reply['user'] = $reply->user;

        $reply->user->avatar = render_cover($reply->user->avatar, 'avatar');

        return ajax('200', '回答问答成功', $reply);
    }

}