<?php
/**
 * Created by PhpStorm.
 * User: wangbaolong
 * Date: 2018/4/18
 * Time: 10:22
 */

namespace App\Http\Transformers;

use App\Enums\FavoriteType;
use App\Enums\ProductType;
use App\Models\Course;
use App\Models\Favorite;
use App\Models\Note;
use App\Models\Topic;

class FavoriteTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected $availableIncludes = ['user'];

    /**
     * @var array 
     */
    protected $defaultIncludes = ['model'];

    /**
     * @param Favorite $model
     * @return array
     */
    public function transform(Favorite $model)
    {
        return [
            'id' => $model->id,
            'model_id' => $model->model_id,
            'model_type' => $model->model_type,
            'user_id' => $model->user_id,
            'created_at' => $model->created_at ? $model->created_at->toDateTimeString() : null,
            'updated_at' => $model->updated_at ? $model->updated_at->toDateTimeString() : null,
        ];
    }

    /**
     * 课程/话题/笔记
     *
     * @param Favorite $model
     * @return \League\Fractal\Resource\Collection|\League\Fractal\Resource\Item|\League\Fractal\Resource\NullResource
     */
    public function includeModel(Favorite $model)
    {
        switch ($model->model_type) {
            case FavoriteType::COURSE:
                return $this->setDataOrItem($model->model, new CourseTransformer());
                break;
            case FavoriteType::TOPIC:
                return $this->setDataOrItem($model->model, new NoteTransformer());
                break;
            case FavoriteType::NOTE:
                return $this->setDataOrItem($model->model, new TopicTransformer());
                break;
            default:
                return $this->null();
        }
    }

    /**
     * 用户
     *
     * @param Favorite $model
     * @return \League\Fractal\Resource\Collection|\League\Fractal\Resource\Item|\League\Fractal\Resource\NullResource
     */
    public function includeUser(Favorite $model)
    {
        return $this->setDataOrItem($model->user, new UserTransformer());
    }
}