<?php

namespace App\Http\Controllers\Front\Manage;

use App\Models\File;
use App\Models\Homework;
use App\Models\HomeworkGrade;
use App\Models\ModelHasTag;
use App\Models\Tag;
use App\Models\TagGroup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeworkController extends Controller
{
    /**
     * 作业列表
     */
    public function index(Request $request)
    {
        $uid = auth('web')->id();
        $title = $request->title;
        $tag = $request->label;
        $homeworks = Homework::when($title, function ($query) use ($title) {
            return $query->where('title', 'like', '%'.$title.'%');
        })->when($tag, function ($query) use ($tag) {
            $tids = Tag::where('name', $tag)->pluck('id');
            $mids = ModelHasTag::where('model_type', 'homework')
                ->whereIn('tag_id', $tids)
                ->pluck('model_id');
            return $query->whereIn('id', $mids);
        })->orderByDesc('id')->paginate(config('theme.teacher_num'));

        $labels = Tag::select('id', 'name as text')
            ->where(function ($q){
                return $q->where('tag_group_id', TagGroup::where('name', 'course')->first()->id);
            })->get();

        return view('teacher.homework.list', compact('homeworks', 'labels'));
    }

    /**
     * 作业创建页面
     */
    public function create()
    {
        $grades = HomeworkGrade::where('user_id', auth('web')->id())->with('user')->get();
        $labels = Tag::select('id', 'name as text')
            ->where(function ($q){
                return $q->where('tag_group_id', TagGroup::where('name', 'course')->first()->id);
            })->get()->toArray();

        $labels = json_encode($labels);
        return view('teacher.homework.add', compact('grades', 'labels'));
    }

    /**
     * 保存作业
     */
    public function store(Request $request, Homework $homework)
    {
        if (!$request->filled(['title', 'grades', 'post_type', 'hint', 'about', 'time'])) {
            return ajax('300', '*号内容必须填写');
        }

        //处理评分标准
        $grades = [];
        $gradesNum = 0;
        foreach ($request->grades as $v) {
            $grades[$v] = $request['grade_num_' . $v];
            $gradesNum += $request['grade_num_' . $v];
        }

        //如果不等于100分, 直接返回
        if ($gradesNum != 100) {
            return ajax('300', '总分数不等于100, 请重新设置');
        }

        $uid = auth('web')->id();
        $homework->fill(array_only($request->all(), [
            'title', 'type', 'post_type', 'status', 'hint', 'grades_content', 'about', 'video', 'package'
        ]));

        $homework->grades = $grades;
        $homework->time = $request->time * 60;
        $homework->grades_content = $request->grades_content;
        $homework->user_id = $uid;
        $homework->save();

        // 如果有标签, 处理标签
        $tags = $request->input('tags', []);
        $homework->tags()->sync($tags);

        if (!empty($request->video_hash)) {
            $checkFile = File::where('hash', $request->hash)->first();
            if (empty($checkFile)) {
                $file = new File();
                // 没有资源就插入, 先查询文件信息
                $file->origin_name = $request->video;
                $file->name = $request->video;
                $file->hash = $request->video_hash;
                $file->url = $request->video;
                $file->task_id = 0;
                $file->user_id = auth('web')->id();
                $file->length = 0;
                $file->save();
            }
        }

        if (!empty($request->package_hash)) {
            $checkFile = File::where('hash', $request->hash)->first();
            if (empty($checkFile)) {
                $file = new File();
                // 没有资源就插入, 先查询文件信息
                $file->origin_name = $request->package;
                $file->name = $request->package;
                $file->hash = $request->package_hash;
                $file->url = $request->package;
                $file->task_id = 0;
                $file->user_id = auth('web')->id();
                $file->length = 0;
                $file->save();
            }
        }
        return ajax('200', '作业添加成功!', $request->all());
    }

    /**
     * 作业详情
     */
    public function show(Homework $homework)
    {
        return view('teacher.homework.modal.preview-modal', compact('homework'));
    }

    /**
     * 作业编辑页面
     */
    public function edit($id)
    {
        //
    }

    /**
     * 更新作业
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 作业启用或者禁用
     */
    public function status(Homework $homework)
    {
        if ($homework->status == 'draft') {
            $homework->status = 'published';
        } else {
            $homework->status = 'draft';
        }
        $homework->save();
        $msg = $homework->status == 'published' ? '作业发布成功!' : '取消发布成功!';
        return ajax('200', $msg);
    }

    /**
     * 删除作业
     */
    public function destroy($id)
    {
        //
    }

    /**
     * 添加作业批改标准
     */
    public function gradeAdd(Request $request, HomeworkGrade $grade)
    {
        if (!$request->filled(['title', 'comment_bad', 'comment_middle', 'comment_good', 'remarks'])) {
            return ajax('300', '所有内容必须填写');
        }

        if (!empty($request->comment_bad)) {
            $grade->comment_bad = explode("\r\n", trim($request->comment_bad, "\r\n"));
        }

        if (!empty($request->comment_middle)) {
            $grade->comment_middle = explode("\r\n", trim($request->comment_middle, "\r\n"));
        }

        if (!empty($request->comment_good)) {
            $grade->comment_good = explode("\r\n", trim($request->comment_good, "\r\n"));
        }

        $grade->title = $request->title;
        $grade->remarks = $request->remarks;
        $grade->user_id = auth('web')->id();
        $grade->save();

        $grade->username = auth('web')->user()->username;

        return ajax('200', '成功', $grade);
    }

    public function gradeShow(HomeworkGrade $grade)
    {
        return view('teacher.homework.modal.grade-show-modal', compact('grade'));
    }
}
