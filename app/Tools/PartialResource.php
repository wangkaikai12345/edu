<?php

namespace App\Tools;

use AetherUpload\ConfigMapper;
use AetherUpload\PartialResource as BasePartialResource;
use Dingo\Api\Exception\ResourceException;


class PartialResource extends BasePartialResource
{
    /**
     * 检测文件大小
     *
     * @param $resourceSize
     */
    public function filterBySize($resourceSize)
    {
        $maxSize = (int)ConfigMapper::get('resource_maxsize');

        if ((int)$resourceSize === 0 || ((int)$resourceSize > $maxSize && $maxSize !== 0)) {
            throw new ResourceException('文件大小不合法', ['resource_size' => '文件大小不合法']);
        }

    }

    /**
     * 检测文件后缀
     *
     * @param $resourceExt
     */
    public function filterByExtension($resourceExt)
    {
        $extensions = ConfigMapper::get('resource_extensions');

        if (empty($resourceExt) || (empty($extensions) === false && in_array($resourceExt, $extensions) === false) || in_array($resourceExt, ConfigMapper::get('forbidden_extensions')) === true) {
            throw new ResourceException('文件不合法', ['resource_name' => '文件不合法']);
        }
    }


    /**
     * 创建文件夹
     *
     * @throws \Exception
     */
    public function create()
    {
        if ($this->createGroupSubDir() === false) {
            throw new \Exception(trans('aetherupload::messages.create_subfolder_fail'));
        }

        if ($this->disk->put($this->path, '') === false) {
            throw new \Exception(trans('aetherupload::messages.create_resource_fail'));
        }
    }


    /**
     * 创建文件夹
     *
     * @return bool
     */
    public function createGroupSubDir()
    {
        $groupDir = dirname($groupSubDir = $this->getGroupSubDirPath());

        if ($this->disk->exists($groupDir) === false) {
            if ($this->disk->createDir($groupDir) === false) {
                return false;
            }
        }

        if ($this->disk->exists($groupSubDir) === false) {
            if ($this->disk->makeDirectory($groupSubDir) === false) {
                return false;
            }
        }

        return true;
    }


    /**
     * 文件路径
     *
     * @return string
     */
    public function getGroupSubDirPath()
    {
        $root_dir = ConfigMapper::get('root_dir');

        $dirPath = $this->groupDir . DIRECTORY_SEPARATOR . $this->groupSubDir;;

        if (empty($root_dir)) {
            return $dirPath;
        }

        return $root_dir . DIRECTORY_SEPARATOR . $dirPath;
    }
}