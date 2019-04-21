<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class TaskTargetType extends Enum
{
    /**
     * @const string 考试
     */
    const PAPER = 'paper';
    /**
     * @const string 视频
     */
    const VIDEO = 'video';
    /**
     * @const string 音频
     */
    const AUDIO = 'audio';   // 音频
    /**
     * @const string PPT
     */
    const PPT = 'ppt';
    /**
     * @const string Doc
     */
    const DOC = 'doc';
    /**
     * @const string 图文
     */
    const TEXT = 'text';

    /**
     * @const string 作业
     */
    const HOMEWORK = 'homework';
    /**
     * @const string 练习
     */
    const PRACTICE = 'practice';
    /**
     * @const string 资料
     */
    const ZIP = 'zip';

    /**
     * 描述信息
     *
     * @param string $value
     * @return string
     */
    public static function getDescription($value): string
    {
        switch ($value) {
            case self::PAPER:
                return '考试';
                break;
            case self::HOMEWORK:
                return '作业';
                break;
            case self::VIDEO:
                return '视频';
                break;
            case self::AUDIO:
                return '音频';
                break;
            case self::PPT:
                return 'PPT';
                break;
            case self::DOC:
                return 'Doc';
                break;
            case self::TEXT:
                return '图文';
                break;
            case self::PRACTICE:
                return '练习';
                break;
            case self::ZIP:
                return '资料';
                break;
            default:
                return self::getKey($value);
        }
    }
}
