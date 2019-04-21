<?php

namespace App\Http\Controllers\Web;

use App\Enums\CloudStorageFileType;
use App\Enums\SettingType;
use App\Http\Controllers\Controller;
use App\Models\Audio;
use App\Models\Doc;
use App\Models\Image;
use App\Models\Ppt;
use App\Models\Video;
use App\Models\Setting;
use App\Rules\CustomEnumRule;
use App\Services\Uploader;
use Illuminate\Http\Request;
use Qiniu\Http\Error;

class QiniuController extends Controller
{
    // 标签
    /**
     * @SWG\Tag(name="uploader",description="七牛云上传")
     */
    private $uploader;

    public function __construct()
    {
        // 获取七牛配置
        if (!($setting = Setting::where('namespace', SettingType::QINIU)->value('value'))) {
            $this->response->errorBadRequest(__('Setting does not exists.'));
        }

        $this->uploader = new Uploader($setting);
    }

    /**
     * @SWG\Get(
     *  path="/qiniu/token",
     *  tags={"uploader"},
     *  summary="获取七牛上传 Token",
     *  produces={"application/json"},
     *  @SWG\Parameter(in="query",name="type",type="string",enum={"video","audio","ppt","doc","image"},default="public",description="视频、音频、PPT、DOC等"),
     *  @SWG\Parameter(in="query",name="hash",type="string",description="文件 Hash"),
     *  @SWG\Response(response=200,description="当已存在资源时，返回资源对应的 Video",@SWG\Schema(
     *    @SWG\Property(property="domain",type="string",description="域名"),
     *    @SWG\Property(property="media_uri",type="string",description="图片上传名称"),
     *    @SWG\Property(property="token",type="string",description="token"),
     *  )),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function token(Request $request)
    {
        $this->validate($request, [
            'type' => ['required', new CustomEnumRule(CloudStorageFileType::class)],
            'hash' => 'required|string'
        ]);

        // 查询是否已存在文件
        $model = resolve('App\\Models\\' . studly_case($request->type));
        if ($media = $model->where('hash', $request->hash)->first()) {
            return $this->response->item($media, resolve('App\\Http\\Transformers\\' . studly_case($request->type) . 'Transformer'));
        }

        $prefix = $request->type . '/';

        // 仅当为视频时，会进入私有库之中
        if ($request->type === CloudStorageFileType::VIDEO) {
            return $this->response->array($this->uploader->privateToken($prefix));
        } else {
            return $this->response->array($this->uploader->token($prefix));
        }
    }

    /**
     * @SWG\Delete(
     *  path="/qiniu/delete/{key}",
     *  tags={"uploader"},
     *  summary="删除文件",
     *  produces={"application/json"},
     *  @SWG\Parameter(in="path",name="key",type="string",required=true,description="图片名称"),
     *  @SWG\Response(response=204,description=""),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function destroy($key)
    {
        $response = $this->uploader->delete($key);
        if ($response instanceof Error) {
            $this->response->error($response->message(), $response->code());
        }

        return $this->response->noContent();
    }

    /**
     * @SWG\Post(
     *  path="/qiniu/database",
     *  tags={"uploader"},
     *  summary="当上传成功后的回调，用于在数据库中添加对应的数据记录",
     *  description="前端上传完成后，必须执行此回调，否则可能无法实现其他功能",
     *  produces={"application/json"},
     *  @SWG\Parameter(in="formData",name="media_uri",type="string",required=true,description="资源名称"),
     *  @SWG\Parameter(in="formData",name="hash",type="string",required=true,description="文件加密hash"),
     *  @SWG\Parameter(in="formData",name="type",type="string",enum={"video","audio","ppt","doc","image"},required=true,description="类型"),
     *  @SWG\Response(response=201,description="ok"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function toDatabase(Request $request)
    {
        $this->validate($request, [
            'media_uri' => 'required|string',
            'hash' => 'required|string',
            'type' => ['required', new CustomEnumRule(CloudStorageFileType::class)],
        ]);

        if ($request->type !== CloudStorageFileType::VIDEO && !$stat = $this->uploader->stat($request->media_uri)) {
            $this->response->errorBadRequest(__('Recourse loss.'));
        }

        switch ($request->type) {
            case 'image':
                $media = Image::firstOrCreate($request->only('media_uri'), [
                    'hash' => $request->hash,
                    'length' => $stat['fsize'],
                ]);
                break;
            case 'video':
                // 获取视频长度
                $response = $this->uploader->privateAvInfo($request->media_uri);
                $length = floor($response['format']['duration']);

                // 七牛切片（切片成功与否均保存到数据库之中）
                $response = $this->uploader->persistentVideo($request->media_uri);
                $media = Video::firstOrCreate([
                    'media_uri' => $request->media_uri,
                    'hash' => $request->hash,
                ], ['status' => 'slicing', 'length' => $length]);

                // 当切片失败后
                if (empty($response['id'])) {
                    $media->status = 'unsliced';
                    $media->save();
                    \Log::useFiles(storage_path('logs/qiniu.log'));
                    \Log::error($response);
                    $this->response->error(__('Sliced error.'), 500);
                }
                break;
            case 'audio':
                $media = Audio::firstOrCreate([
                    'media_uri' => $request->media_uri,
                    'hash' => $request->hash,
                    'length' => 0
                ]);
                break;
            case 'ppt':
                $media = Ppt::firstOrCreate([
                    'media_uri' => $request->media_uri,
                    'hash' => $request->hash,
                    'length' => $request->length ?? 0,
                ]);
                break;
            case 'doc':
                $media = Doc::firstOrCreate([
                    'media_uri' => $request->media_uri,
                    'hash' => $request->hash,
                    'length' => $request->length ?? 0,
                ]);
                break;
        }

        return $this->response->item($media, resolve('App\\Http\\Transformers\\' . studly_case($request->type) . 'Transformer'));
    }

    /**
     * @SWG\Get(
     *  path="/qiniu/video/status",
     *  tags={"uploader"},
     *  summary="获取切片状态",
     *  description="请使用私有库",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="driver",in="query",type="string",enum={"public","private"},default="public",description="七牛公共库、私有库"),
     *  @SWG\Parameter(in="query",name="id",type="string",description="上一步返回的 ID"),
     *  @SWG\Response(response=200,description="",@SWG\Schema(
     *    @SWG\Property(property="ret",type="string",description="状态")
     *  )),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function persistentStatus()
    {
        $id = request('id', null);
        $response = $this->uploader->persistentStatus($id);

        if ($response instanceof Error) {
            $this->response->error($response->message(), $response->code());
        }

        return $this->response->array(['ret' => $response['ret']]);
    }

    /**
     * @SWG\Post(
     *  path="/qiniu/avinfo",
     *  tags={"uploader"},
     *  summary="基本信息",
     *  description="获取视频的基本信息，请使用私有库",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="driver",in="query",type="string",enum={"public","private"},default="public",description="七牛公共库、私有库"),
     *  @SWG\Parameter(name="key",in="query",type="string",description="资源名称"),
     *  @SWG\Response(response=200,description=""),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function avinfo()
    {
        $key = request('key');

        $response = $this->uploader->privateAvInfo($key);

        return $this->response->array(json_decode($response, true));
    }

    /**
     * 七牛切片完成后的回调方法
     */
    public function callBack(Request $request, Video $video)
    {
        $key = $request['inputKey'];

        // 查询课程
        $video = $video->where('media_uri', $key)->first();

        $video->status = 'sliced';
        $video->save();

        $resp = array('ret' => 'success');

        return json_encode($resp);
    }
}
