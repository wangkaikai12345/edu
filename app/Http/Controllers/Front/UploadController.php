<?php

namespace App\Http\Controllers\Front;

use AetherUpload\ConfigMapper;
use AetherUpload\RedisSavedPath;
use App\Services\Uploader;
use App\Tools\Resource;
use App\Http\Requests\Front\SaveChunkRequest;
use App\Tools\PartialResource;
use App\Models\File;
use App\Tools\Util;
use Dingo\Api\Exception\ResourceException;
use Facades\App\Models\Setting;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    // 驱动
    protected $disk;

    // 驱动配置文件
    protected $diskConfig;

    // 是否是七牛
    protected $is_qiniu = false;

    // 是否是ajax
    protected $want_json = false;

    // 驱动
    protected $driver;


    public function __construct()
    {
        // 获取驱动配置
        $this->diskConfig = Setting::namespace('qiniu');

        // 获取驱动
        $this->driver = data_get($this->diskConfig, 'driver', config('filesystems.default'));

        // 七牛重写file配置
        if ($this->driver == 'qiniu') {
            $this->is_qiniu = true;
            $this->formatConfig();
        }

        // 获取JSON数据
        $this->want_json = request()->wantsJson();

        // 加载驱动
        $this->disk = Storage::disk($this->driver);
    }


    /**
     * 文件上传
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|void
     * @throws \Exception
     */
    public function upload(Request $request)
    {
        if (true) {
            return $this->saveChunk($request);
        }


        // 文件前缀
        $group = $request->input('group', false);

        // 文件名
        if ($group === false) {
            throw  new ResourceException('非法的文件分组', ['group' => '非法的文件分组']);
        }

        // 文件HASH值
        $resourceHash = $request->input('resource_hash', false);

        // 文件HASH值
        if ($resourceHash === false) {
            throw  new ResourceException('非法文件', ['resource_hash' => '非法文件']);
        }

        // 存在文件HASH值进行秒传操作
        if ($resourceHash) {
            // 查询HASH文件是否已经存在数据库中
            $file = File::where('hash', $resourceHash)->first();

            // 数据已经存在则是秒传
            if (!empty($file)) {
                return $this->response->array($file->toArray());
            }
        }

        // 七牛云上传
        $uploader = new Uploader($this->diskConfig);

        // 获取文件上传配置
        $token = $uploader->token($group);

        // 返回配置信息
        return $this->response->array($token);
    }


    /**
     * 上传文件时的准备工作
     *
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function preprocess(Request $request)
    {
        // 文件名称
        $resourceName = $request->input('resource_name', false);
        // 文件大小
        $resourceSize = $request->input('resource_size', false);
        // 文件HASH值
        $resourceHash = $request->input('resource_hash', false);
        // 文件类型
        $group = $request->input('group', false);

        // 存在文件HASH值进行秒传操作
        if ($resourceHash) {
            // 查询HASH文件是否已经存在数据库中
            $file = File::where('hash', $resourceHash)->first();

            // 数据已经存在则是秒传
            if (!empty($file)) {
                return $this->response->array($file->toArray());
            }
        }

        // 返回的结果集
        $result = [
            'error' => 0,  // 错误码
            'chunkSize' => 0, // 分片大小
            'groupSubDir' => '', // 上传的文件夹
            'resourceTempBaseName' => '', //  资源的文件名
            'resourceExt' => '', // 文件后缀
            'savedPath' => '', // 保存的路径
        ];


        if ($resourceName === false || $resourceSize === false || $group === false) {
            throw new ResourceException('非法文件', ['file' => '非法文件']);
        }

        // 加载配置文件
        ConfigMapper::instance()->applyGroupConfig($group);

        // 设置文件名
        $result['resourceTempBaseName'] = $resourceTempBaseName = Util::generateTempName();

        // 获取文件后缀
        $result['resourceExt'] = $resourceExt = strtolower(pathinfo($resourceName, PATHINFO_EXTENSION));

        // 获取文件的储存位置 按照日期划分
        $result['groupSubDir'] = $groupSubDir = Util::generateSubDirName();

        // 切片分片的大小
        $result['chunkSize'] = ConfigMapper::get('chunk_size');

        // 文件储存配置
        $partialResource = new PartialResource($resourceTempBaseName, $resourceExt, $groupSubDir);

        // 过滤符合大小的文件
        $partialResource->filterBySize($resourceSize);

        // 过滤符合后缀的文件
        $partialResource->filterByExtension($resourceExt);

        // 创建文件夹
        $partialResource->create();

        // 分片上传起始索引
        $partialResource->chunkIndex = 0;

        // 返回结果
        return $this->response->array($result);
    }


    /**
     * 分片保存
     *
     * @return mixed
     * @throws \Exception
     */
    public function saveChunk(Request $request)
    {
        resolve(SaveChunkRequest::class);

        // 分片总数量
        $chunkTotalCount = $request->input('chunk_total', false);
        // 分片索引
        $chunkIndex = $request->input('chunk_index', false);
        // 文件名
        $resourceTempBaseName = $request->input('resource_temp_basename', false);
        // 文件后缀
        $resourceExt = $request->input('resource_ext', false);
        // 获取分片的文件数据
        $chunk = $request->file('resource_chunk', false);
        // 保存的路径
        $groupSubDir = $request->input('group_subdir', false);
        // 文件hash值
        $resourceHash = $request->input('resource_hash', false);
        // 分组
        $group = $request->input('group', false);

        // 储存路径
        $savedPathKey = Util::getSavedPathKey($group, $resourceHash);

        // 存在文件HASH值进行秒传操作
        if ($resourceHash) {
            // 查询HASH文件是否已经存在数据库中
            $file = File::where('hash', $resourceHash)->first();

            // 数据已经存在则是秒传
            if (!empty($file)) {
                return $this->response->array($file->toArray());
            }
        }

        // 保存结果
        $partialResource = null;

        // 结果集
        $result = [
            'error' => 0,
            'savedPath' => '',
        ];

        // 配置
        ConfigMapper::instance()->applyGroupConfig($group);

        // 保存路径
        $partialResource = new PartialResource($resourceTempBaseName, $resourceExt, $groupSubDir);

        // do a check to prevent security intrusions
        if ($partialResource->exists() === false || $chunk->getError() > 0 || $chunk->isValid() === false) {
            $this->deleteFile($partialResource);
            throw new ResourceException('文件上传失败', ['resource_temp_basename' => '文件上传失败']);
        }


        if ((int)($partialResource->chunkIndex) !== (int)$chunkIndex - 1) {
            return $this->response->array($result);
        }

        $partialResource->append($chunk->getRealPath());

        $partialResource->chunkIndex = $chunkIndex;

        // 分片等于总片数
        if ($chunkIndex === $chunkTotalCount) {

            $partialResource->checkSize();

            $partialResource->checkMimeType();

            // 上传完成前触发的事件
            if (empty($beforeUploadCompleteEvent = ConfigMapper::get('event_before_upload_complete')) === false) {
                event(new $beforeUploadCompleteEvent($partialResource));
            }

            // 文件HASH
            $resourceHash = $partialResource->calculateHash();

            // 分片重命名
            $partialResource->rename($completeName = Util::getFileName($resourceHash, $resourceExt));

            $resource = new Resource($completeName, $groupSubDir);

            // 获取保存的路径
            $savedPath = $resource->getSavedPath();

            if (ConfigMapper::get('instant_completion') === true) {
                RedisSavedPath::set($savedPathKey, $savedPath);
            }

            unset($partialResource->chunkIndex);

            // 上传完成后触发的事件
            if (empty($uploadCompleteEvent = ConfigMapper::get('event_upload_complete')) === false) {
                event(new $uploadCompleteEvent($resource));
            }

            $result['savedPath'] = $savedPath;
        }

        return $this->response->array($result);
    }


    /**
     * 删除文件
     *
     * @param $partialResource
     */
    public function deleteFile($partialResource)
    {
//        $partialResource->delete();
//        unset($partialResource->chunkIndex);
    }


    /**
     * 重置配置文件
     */
    protected function formatConfig()
    {
        $settingKeys = [
            "access_key" => "ak",
            "secret_key" => "sk",
            "bucket" => "public_bucket",
            "notify_url" => "callback",
        ];

        foreach ($settingKeys as $key => $item) {
            config(['filesystems.disks.qiniu.' . $key => $this->diskConfig[$item]]);
        }
    }
}
