<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class CloudStorageFileType extends Enum
{
    /**
     * @const string 图片
     */
    const IMAGE = 'image';
    /**
     * @const string 视频
     */
    const VIDEO = 'video';
    /**
     * @const string 音频
     */
    const AUDIO = 'audio';
    /**
     * @const string Word
     */
    const DOC = 'doc';
    /**
     * @const string PPT
     */
    const PPT = 'ppt';

    /**
     * 描述信息
     *
     * @param string $value
     * @return string
     */
    public static function getDescription($value): string
    {
        switch ($value) {
            case self::IMAGE:
                return '图片资源';
                break;
            case self::VIDEO:
                return '视频资源';
                break;
            case self::DOC:
                return '文档资源';
                break;
            case self::PPT:
                return 'PPT资源';
                break;
            case self::AUDIO:
                return '音频资源';
                break;
            default:
                return self::getKey($value);
        }
    }
}
