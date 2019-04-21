<?php
/**
 * Created by PhpStorm.
 * TestResult: wangbaolong
 * Date: 2018/4/18
 * Time: 10:22
 */

namespace App\Http\Transformers;

use App\Models\TestResult;

class TestResultTransformer extends BaseTransformer
{

    /**
     * @var array
     */
    protected $availableIncludes = ['user', 'test'];

    /**
     * @var array
     */
    protected $defaultIncludes = [];

    public function transform(TestResult $model)
    {
        return [
            'id' => $model->id,
            'user_id' => $model->user_id,
            'test_id' => $model->test_id,
            'right_count' => $model->right_count,
            'questions_count' => $model->questions_count,
            'is_finished' => (boolean)$model->is_finished,
            'finished_count' => $model->finished_count,
            'total_score' => $model->score,
            'score' => $model->score,
            'created_at' => $model->created_at ? $model->created_at->toDateTimeString() : null,
            'updated_at' => $model->updated_at ? $model->updated_at->toDateTimeString() : null,
            'deleted_at' => $model->deleted_at ? $model->deleted_at->toDateTimeString() : null,
        ];
    }

    /**
     * 创建人
     */
    public function includeUser(TestResult $model)
    {
        return $this->setDataOrItem($model->user, new UserTransformer());
    }

    /**
     * 所属课程
     */
    public function includeTest(TestResult $model)
    {
        return $this->setDataOrItem($model->test, new TestTransformer());
    }
}