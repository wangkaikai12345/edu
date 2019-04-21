<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class TaskType extends Enum
{
    /**
     * @const string  测试
     */
    const TEST = 'a-test';

    /**
     * @const string 开篇
     */
    const INTRODUCE = 'b-introduce';
    /**
     * @const string 任务
     *
     */
    const TASK = 'c-task';
    /**
     * @const string 作业
     */
    const HOMEWORK = 'd-homework';

    /**
     * @const string 考试
     */
//    const EXAM = 'e-exam';
    /**
     * @const string 拓展
     */
    const EXTRA = 'f-extra';


    /**
     * 描述信息
     *
     * @param string $value
     * @return string
     */
    public static function getDescription($value): string
    {
        switch ($value) {
            case self::TEST:
                return '测试';
                break;
            case self::INTRODUCE:
                return '开篇';
                break;
            case self::TASK:
                return '任务';
                break;
            case self::HOMEWORK:
                return '作业';
                break;
//            case self::EXAM:
//                return '考试';
//                break;
            case self::EXTRA:
                return '拓展';
                break;
            default:
                return self::getKey($value);
        }
    }

}
