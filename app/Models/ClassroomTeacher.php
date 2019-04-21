<?php

namespace App\Models;

use App\Enums\TeacherType;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;

/**
 * @SWG\Definition(
 *     definition="ClassroomTeacher",
 *     type="object",
 *     required={},
 *     description="班级教师",
 *     @SWG\Property(property="id",type="integer",readOnly=true),
 *     @SWG\Property(property="classroom_id",type="integer",description="班级"),
 *     @SWG\Property(property="user_id",type="integer",description="教师"),
 *     @SWG\Property(property="type",type="string",enum={"head","teacher","assistant"},description="班主任/授课教师/助教"),
 *     @SWG\Property(property="created_at",type="string",format="date-time",description="创建时间",readOnly=true),
 *     @SWG\Property(property="updated_at",type="string",format="date-time",description="更新时间",readOnly=true)
 * )
 *
 * // 分页响应
 * @SWG\Response(
 *   response="ClassroomTeacherPagination",
 *   description="",
 *   @SWG\Schema(
 *     @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/ClassroomTeacher")),
 *     @SWG\Property(property="meta",ref="$/definitions/MetaProperty")
 *   )
 * )
 *
 * // 基础响应
 * @SWG\Response(
 *   response="ClassroomTeacherResponse",
 *   description="",
 *   @SWG\Schema(
 *     @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/ClassroomTeacher")),
 *   )
 * )
 *
 * // Fillable parameters 表单参数
 * @SWG\Parameter(parameter="ClassroomTeacherForm-user_id",name="user_id",required=true,in="formData",type="integer",description="用户")
 * @SWG\Parameter(parameter="ClassroomTeacherForm-type",name="status",required=true,in="formData",type="string",enum={"head","teacher","assistant"},description="班主任/授课教师/助教")
 *
 * // Searchable parameters 搜索参数
 * @SWG\Parameter(parameter="ClassroomTeacherQuery-user_id",name="user_id",in="query",type="string",description="用户ID")
 * @SWG\Parameter(parameter="ClassroomTeacherQuery-classroom_id",name="classroom_id",in="query",type="integer",description="班级ID")
 * @SWG\Parameter(parameter="ClassroomTeacherQuery-type",name="type",in="query",type="string",enum={"head","teacher","assistant"},description="班主任/授课教师/助教")
 * @SWG\Parameter(parameter="ClassroomTeacherQuery-created_at",name="created_at",in="query",type="string",description="创建时间")
 * @SWG\Parameter(parameter="ClassroomTeacherQuery-user:username",name="created_at",in="query",type="string",description="用户名")
 *
 * // sortable parameters 排序参数
 * @SWG\Parameter(
 *     parameter="ClassroomTeacher-sort",
 *     name="sort",
 *     in="query",
 *     type="string",
 *     description="使用方法见 sortable；[created_at]",
 * )
 *
 * // Include parameters 关联参数
 * @SWG\Parameter(
 *     parameter="ClassroomTeacher-include",
 *     name="include",
 *     in="query",
 *     type="string",
 *     description="使用方法见 Include；{user:用户,classroom:班级}",
 * )
 */
class ClassroomTeacher extends Model
{
    use SearchableTrait, SortableTrait;

    /**
     * @var string
     */
    protected $table = 'classroom_teachers';
    /**
     * @var array
     */
    protected $fillable = [
        'classroom_id',
        'user_id',
        'type',
    ];

    /**
     * 班级
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    /**
     * 用户
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 班主任
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHead($query)
    {
        return $query->where('type', TeacherType::HEAD);
    }

    /**
     * 教师
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTeacher($query)
    {
        return $query->where('type', TeacherType::TEACHER);
    }

    /**
     * 助教
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAssistant($query)
    {
        return $query->where('type', TeacherType::ASSISTANT);
    }
}
