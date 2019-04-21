<?php

namespace App\Http\Controllers\Front\Manage;

use App\Enums\SettingType;
use App\Enums\TaskTargetType;
use App\Enums\TaskResultStatus;
use App\Enums\VideoStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Front\TaskRequest;
use App\Models\Chapter;
use App\Models\File;
use App\Models\Homework;
use App\Models\Paper;
use App\Models\PlanMember;
use App\Models\Task;
use App\Models\TaskResult;
use App\Models\Text;
use DB;
use Facades\App\Models\Setting;
use Illuminate\Http\Request;
use Qiniu\Processing\PersistentFop;

class TaskController extends Controller
{
    /**
     * 查看文件详细信息
     */
    public function getFileInfo($type, $key = null)
    {
        $config = Setting::namespace(SettingType::QINIU);

        if ($type == 'video' || $type == 'audio') {
            $domain = $config['private_domain'];
        } else {
            $domain = $config['public_domain'];
        }

        $auth = new \Qiniu\Auth($config['ak'], $config['sk']);
        $operation = new \Qiniu\Processing\Operation($domain, $auth);
        $fops = 'avinfo';
        return $operation->execute($key, $fops);
    }

    /**
     * 音视频切片处理
     */
    public function qiniuSlice($mkey)
    {
        $config = Setting::namespace(SettingType::QINIU);

        $key = $mkey; // 源文件的key
        $savekey = \Qiniu\base64_urlSafeEncode($config['slice_bucket'] . ":{$key}"); // 处理后的文件的key
        $hlsName = \Qiniu\base64_urlSafeEncode($mkey . '/' . '$(count)' . '.ts'); // 切片的后每一片的名字

        // hls-rsa
        $hlsKey = config('app.hls_key');
        $hlsType = '1.0';
        $hlsUrl = \Qiniu\base64_urlSafeEncode(config('app.url')."/qiniu/hls");

        $hlsEncrypt = '/hlsKey/' . $hlsKey . '/hlsKeyType/' . $hlsType . '/hlsKeyUrl/' . $hlsUrl;
        $fops = 'avthumb/m3u8/noDomain/1/segtime/5/aq/0/vb/8m' . $hlsEncrypt . '/pattern/' . $hlsName . '|' . 'saveas/' . $savekey;
        $auth = new \Qiniu\Auth($config['ak'], $config['sk']); // 七牛认证
        $pfop = new PersistentFop($auth); // 使用

        $bucket = $config['private_bucket']; // 源文件所有在的库
        $pipeline = $config['queue']; // 要使用的队列
        $notifyUrl = $config['callback']; // 处理成功之后的通知地址
        $force = 1; // 当文件存在的时候, 是否强制覆盖

        list($id, $err) = $pfop->execute($bucket, $key, $fops, $pipeline, $notifyUrl, $force);

        // avthumb/m3u8/noDomain/1/segtime/5/aq/0/vb/8m/hlsKey//hlsKeyType/1.0/hlsKeyUrl/aHR0cDovL3Rlc3QueWRtYS5jbi9xaW5pdS9obHM=/pattern/RnV0dElNZm5tSUNQdHJKOXktVmd1U0pIc2o0ai8kKGNvdW50KS50cw==|saveas/eXVuLXNsaWNlOkZ1dHRJTWZubUlDUHRySjl5LVZndVNKSHNqNGo=

    }

    /**
     * 任务创建
     */
    public function store(Chapter $chapter, Request $request, Task $task, File $file)
    {

        try {
            DB::beginTransaction();

            $plan = $chapter->plan;

            $taskCount = $chapter->tasks()->count();

            $request->target_type && $request->target_type == 'practice' ? $request->offsetSet('target_type', 'homework') : '';

            $model = resolve('App\\Models\\' . studly_case($request->target_type));

            // 获取驱动
            $driver = data_get(\Facades\App\Models\Setting::namespace('qiniu'), 'driver', 'local');

            // 如果存在hash, 查询七牛中的文件信息
            $fileInfo = [];
            if (!empty($request->hash) && $driver == 'qiniu') {
                $fileInfo = $this->getFileInfo($request->target_type, $request->media_uri);
            }

            // 资源信息
            switch ($request->target_type) {
                // 视频、音频、PPT、DOC
                case 'video':
                case 'audio':
                case 'ppt':
                case 'doc':
                case 'zip':
                    $media = $model->where($request->only('media_uri'))->first();
                    if (empty($media)) {
                        $media = new $model;
                        // 创建
                        $media->fill($request->only('media_uri', 'hash'));
                        $media->length = ($driver == 'qiniu' ? (!empty($fileInfo[0]['format']['duration']) ? ceil($fileInfo[0]['format']['duration']) : 0 ): getLocalLength($request->media_uri));
                        $media->save();
                    }
                    break;
                // 图文
                case 'text':
                    $media = $model->create($request->only(['body']));
                    break;
                // 考试 此处传递的是ID
                case 'paper':
                case 'homework':
                    $media = $model->find($request->media_uri);
                    break;
                default:
                    $media = null;
            }

            if (!$media) {
                return ajax('400', __('Recourse loss.'));
            }

            $task->fill(request()->only([
                'title',
                'type',
                'is_free',
                'is_optional',
                'length',
                'started_at',
                'ended_at',
                'finish_type',
                'finish_detail',
                'describe','keyword'
            ]));

            $task->user_id = auth('web')->id();
            $task->course_id = $plan->course_id;
            $task->plan_id = $plan->id;
            $task->chapter_id = $chapter->id;
            $task->target_type = $request->target_type;
            $task->target_id = $media->id;
            $task->length = isset($media->length) ? $media->length : ($request->length * 60 ?? 0);
            $task->seq = $taskCount + 1;
            $task->save();

            // 如果目标类型是视频或者是音频, 执行切片操作
            if ($request->target_type == 'video') {
                $driver == 'qiniu' ? $this->qiniuSlice($request->media_uri) : '';
                // 修改状态
                $media->status = $driver == 'qiniu' ? 'slicing' : 'sliced';
                $media->save();
            }

            if (!empty($request->hash)) {
                $checkFile = File::where('hash', $request->hash)->first();
                if (empty($checkFile)) {
                    // 没有资源就插入, 先查询文件信息
                    $file->origin_name = $request->media_uri;
                    $file->name = $request->media_uri;
                    $file->hash = $request->hash;
                    $file->url = $request->media_uri;
                    $file->task_id = $task->id;
                    $file->user_id = auth('web')->id();
                    $file->length = ($driver == 'qiniu' ? ceil($fileInfo[0]['format']['size'] / 1000000) : getLocalLength($request->media_uri));
                    $file->save();
                }
            }

            DB::commit();
            return ajax(200, 'ok');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::info($e);
            return ajax(400, $e->getMessage());
        }

    }

    /**
     * 任务编辑
     *
     * @param Chapter $chapter
     * @param Task $task
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author 王凯
     */
    public function edit(Chapter $chapter, Task $task)
    {
        return view('teacher.plan.modal.edit-task-modal', compact('chapter', 'task'));
    }

    /**
     * 任务更新
     *
     * @param Chapter $chapter
     * @param $task
     * @param TaskRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @author 王凯
     */
    public function update(Chapter $chapter, Task $task, TaskRequest $request, File $file)
    {
        $request->target_type && $request->target_type == 'practice' ? $request->offsetSet('target_type', 'homework') : '';

        $model = resolve('App\\Models\\' . studly_case($request->target_type));

        // 更改资源信息
        switch ($request->target_type) {
            // 视频、音频、PPT、DOC
            case 'video':
            case 'audio':
            case 'ppt':
            case 'doc':
            case 'zip':
                // 仅更改基本信息(视音频)
                if ($task->target->media_uri && $request->media_uri == $task->target->media_uri) {

                    $task->fill($request->only([
                        'title', 'type', 'is_free',
                        'is_optional', 'started_at',
                        'ended_at', 'finish_type', 'finish_detail',
                        'describe','keyword'
                    ]));
                    $task->save();

                    return ajax('200', '编辑成功');
                }
                $media = $model->where('id', $request->target_id)->first();

                break;
            // 图文、考试 此处传递的是ID
            case 'text':
                !empty($request->length) ? $request->offsetSet('length', $request->length * 60) : '';

                $task->fill($request->only([
                    'title', 'type', 'is_free', 'length',
                    'is_optional', 'started_at', 'ended_at', 'finish_type', 'finish_detail', 'describe','keyword'
                ]));
                $task->save();

                $textContent = $model->find($request->target_id);
                $textContent->body = $request->body;
                $textContent->save();

                return ajax('200', '编辑成功');
            case 'paper':
            case 'homework':
                if ($request->target_id == $task->target->id) {

                    !empty($request->length) ? $request->offsetSet('length', $request->length * 60) : '';

                    $task->fill($request->only([
                        'title', 'type', 'is_free', 'length', 'is_optional',
                        'started_at', 'ended_at', 'finish_type', 'finish_detail',
                        'describe','keyword'
                    ]));
                    $task->save();

                    return ajax('200', '编辑成功');
                }
                $media = $model->find($request->target_id);
                break;
            default:
                $media = null;
        }

        if (!$media) {
            return ajax('400', __('Recourse loss.'));
        }

        DB::transaction(function () use ($media, $chapter, $task, $file) {

            // 获取驱动
            $driver = data_get(\Facades\App\Models\Setting::namespace('qiniu'), 'driver', 'local');

            // 如果是更新上传资源
            if ($media->media_uri) {

                // 判断是否进行资源更新操作
                if (request('media_uri') == $media->media_uri && request('hash') == $media->hash) {

                } else {

                    // 判断是否是秒传的文件
                    $checkFile =  File::where('hash', request('hash'))->first();

                    $media->media_uri = request('media_uri');
                    $media->hash = request('hash');
                    $media->length = $driver == 'local' ? getLocalLength(request('media_uri')) : 0;
                    $media->save();

                    if (!$checkFile) {

                        // 如果是视频，切片操作
                        if (request('target_type') == 'video' && $driver == 'qiniu') {

                            $fileInfo = $this->getFileInfo(request('target_type'), request('media_uri'));

                            $this->qiniuSlice(request('media_uri'));

                            // 修改状态
                            $media->status = 'slicing';
                            $media->length = ceil($fileInfo[0]['format']['duration']);
                            $media->save();

                            // 同时任务关闭状态
                            $task->status = 'draft';
                        }
                        // 文件表添加数据
                        $file->origin_name = request('media_uri');
                        $file->name = request('media_uri');
                        $file->hash = request('hash');
                        $file->url = request('media_uri');
                        $file->task_id = $task->id;
                        $file->user_id = auth('web')->id();
                        $file->length = ($driver == 'qiniu' ? (!empty($fileInfo[0]['format']['size']) ? ceil($fileInfo[0]['format']['size'] / 1000000) : 0) : getLocalLength($media->media_uri));
                        $file->save();

                    }
                }


            }

            $task->fill(request()->only([
                'title',
                'type',
                'is_free',
                'is_optional',
                'started_at',
                'ended_at',
                'finish_type',
                'finish_detail'
            ]));

            if ($media instanceof Homework || $media instanceof Paper) {
                $task->target_type = request('target_type');
                $task->target_id = request('media_uri');
                $task->save();
            } else {
                $task->target_type = request('target_type');
                $task->target_id = $media->id;
                $task->length = $media->length ?? 0;
                $task->save();
            }

        });

        return ajax('200', '更新成功');

    }

    /**
     * 任务的发布和取消发布
     *
     * @param Chapter $chapter
     * @param Task $task
     * @return \Illuminate\Http\JsonResponse
     * @author 王凯
     */
    public function publish(Chapter $chapter, Task $task)
    {
        $this->authorize('isPlanTeacher', $chapter->plan);

        $this->validate(request(), ['status' => 'required|in:published,closed']);

        switch ($task->target_type) {
            case TaskTargetType::VIDEO:
                // 判断是否切片成功
                $video = $task->target;
                if ($video->status !== VideoStatus::SLICED) {
                    return ajax('400', __('Video cannot be published without slicing.'));
                }
                break;
            case TaskTargetType::PAPER:
                // 判断是否包含题目
                $test = $task->target;
                if ($test->questions_count <= 0) {
                    return ajax('400', __('Exam cannot be published without questions.'));
                }
                break;
            case TaskTargetType::AUDIO:
            case TaskTargetType::ZIP:
            case TaskTargetType::PPT:
            case TaskTargetType::DOC:
            case TaskTargetType::TEXT:
            case TaskTargetType::HOMEWORK:
                break;
            default:
                return ajax('400', '任务类型错误，无法发布');
        }

        $task->status = request('status');
        $task->save();
        return ajax('200', '任务操作成功!');
    }

    /**
     * 删除任务
     */
    public function delete(Chapter $chapter, $taskId)
    {
        $task = $chapter->tasks()->findOrFail($taskId);

        if ($task->status == 'published') {
            return ajax('400', '已发布任务，不允许删除');
        }

        /**
         * 当删除任务时：
         * 1. 如果任务已发布，那么对 plan 的 tasks_count 进行递减操作；否则不操作。(权限中做了已发布任务无法删除的限制，所以此项不用考虑)
         * 2. 如果任务结果已完成，那么对 PlanMember 中的 learned_count 执行递减操作；否则不操作。
         * 3. 删除任务结果
         *
         * 注：当恢复时，反顺序恢复即可。
         */

        $plan = $task->plan;
        $results = $task->results;

        DB::transaction(function () use ($task, $plan, $results) {
            // 对 plan_member 中的 learned_count 做处理
            $results->each(function ($result) use ($task, $plan) {
                if ($result->status == TaskResultStatus::FINISH) {
                    !$task->is_optional && PlanMember::where('user_id', $result->user_id)->where('plan_id', $plan->id)->decrement('learned_compulsory_count');
                    PlanMember::where('user_id', $result->user_id)->where('plan_id', $plan->id)->decrement('learned_count');
                }
            });
            // 删除任务结果
            TaskResult::whereIn('id', $results->pluck('id')->toArray())->delete();
            // 删除任务
            $task->delete();
        });

        return ajax('200', '删除成功');
    }
}