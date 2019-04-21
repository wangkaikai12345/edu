<?php
/**
 * Created by PhpStorm.
 * User: wangbaolong
 * Date: 2018/4/18
 * Time: 10:22
 */

namespace App\Http\Transformers;

use App\Enums\FavoriteType;
use App\Enums\SettingType;
use App\Models\Course;
use App\Models\Favorite;
use Facades\App\Models\Setting;

class CourseTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected $availableIncludes = ['user', 'plans', 'default_plan', 'tags', 'category'];

    /**
     * @var array
     */
    protected $defaultIncludes = [];

    /**
     * @var string 域名
     */
    public $domain;

    public function __construct()
    {
        $this->domain = Setting::namespace(SettingType::QINIU)['public_domain'];
    }

    public function transform(Course $model)
    {
        return [
            'id' => $model->id,
            'title' => $model->title,
            'subtitle' => $model->subtitle,
            'summary' => $model->summary,
            'category_id' => $model->category_id,
            'goals' => $model->goals,
            'audiences' => $model->audiences,
            'cover' => $model->cover ? $this->domain . '/' . $model->cover : config('app.url') . '/images/cover.png',
            'status' => $model->status,
            'serialize_mode' => $model->serialize_mode,
            'reviews_count' => $model->reviews_count,
            'rating' => $model->rating,
            'notes_count' => $model->notes_count,
            'students_count' => $model->students_count,
            'is_recommended' => $model->is_recommended,
            'recommended_seq' => $model->recommended_seq,
            'recommended_at' => $model->recommended_at,
            'hit_count' => $model->hit_count,
            'copy_id' => $model->copy_id,
            'locked' => $model->locked,
            'min_course_price' => $model->min_course_price,
            'max_course_price' => $model->max_course_price,
            'default_plan_id' => $model->default_plan_id,
            'user_id' => $model->user_id,
            'discount_id' => $model->discount_id,
            'discount' => $model->discount,
            'max_discount' => $model->max_discount,
            'materials_count' => $model->materials_count,
            'is_control' => $this->getIsControl($model),
            'is_favorite' => $this->getIsFavourite($model),
            'plans_count' => $model->plans_count,
            'created_at' => $model->created_at ? $model->created_at->toDateTimeString() : null,
            'updated_at' => $model->updated_at ? $model->updated_at->toDateTimeString() : null,
            'link' => config('app.url').'/courses/'.$model->id,
        ];
    }

    /**
     * 创建者
     */
    public function includeUser(Course $model)
    {
        return $this->setDataOrItem($model->user, new UserTransformer());
    }

    /**
     * 分类
     */
    public function includeCategory(Course $model)
    {
        return $this->setDataOrItem($model->category, new CategoryTransformer());
    }

    /**
     * 版本
     */
    public function includePlans(Course $model)
    {
        return $this->setDataOrItem($model->plans, new PlanTransformer());
    }

    /**
     * 默认版本
     */
    public function includeDefaultPlan(Course $model)
    {
        return $this->setDataOrItem($model->default_plan, new PlanTransformer());
    }

    /**
     * 标签
     */
    public function includeTags(Course $model)
    {
        return $this->setDataOrItem($model->tags, new TagTransformer());
    }

    /**
     * 是否可管理
     *
     * @param Course $model
     * @return bool
     */
    public function getIsControl(Course $model) :bool
    {
        if (!$me = auth()->user()) {
            return false;
        }

        if ($me->isAdmin()) {
            return true;
        }

        return (bool)$me->manageCourses()->where('course_id', $model->id)->count();
    }

    /**
     * 是否收藏
     *
     * @param Course $model
     * @return bool
     */
    public function getIsFavourite(Course $model)
    {
        if (!$me = auth()->user()) {
            return false;
        }
        return Favorite::where('user_id', $me->id)
            ->where('model_type', FavoriteType::COURSE)
            ->where('model_id', $model->id)
            ->exists();
    }
}