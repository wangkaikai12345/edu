<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Sortable\SortableTrait;

/**
 * @SWG\Definition(
 *      definition="Profile",
 *      type="object",
 *      required={},
 *      description="私信模型",
 *      @SWG\Property(property="user_id",type="integer",description="用户",readOnly=true),
 *      @SWG\Property(property="title",type="string",maxLength=16,default=null,description="头衔"),
 *      @SWG\Property(property="name",type="string",maxLength=16,default=null,description="姓名"),
 *      @SWG\Property(property="gender",type="string",enum={"male","female","secret"},default="secret",description="性别"),
 *      @SWG\Property(property="idcard",type="string",minLength=18,maxLength=18,default=null,description="身份证"),
 *      @SWG\Property(property="birthday",type="string",format="date-time",default=null,description="生日"),
 *      @SWG\Property(property="city",type="string",maxLength=16,default=null,description="城市"),
 *      @SWG\Property(property="qq",type="string",maxLength=32,default=null,description="QQ"),
 *      @SWG\Property(property="about",type="string",default=null,description="个人介绍"),
 *      @SWG\Property(property="company",type="string",maxLength=32,default=null,description="公司"),
 *      @SWG\Property(property="job",type="string",maxLength=32,default=null,description="工作"),
 *      @SWG\Property(property="school",type="string",maxLength=32,default=null,description="学校"),
 *      @SWG\Property(property="major",type="string",maxLength=32,default=null,description="专业"),
 *      @SWG\Property(property="weibo",type="string",maxLength=32,default=null,description="微博"),
 *      @SWG\Property(property="is_qq_public",type="bool",default=true,description="是否公开QQ"),
 *      @SWG\Property(property="is_weixin_public",type="bool",default=true,description="是否公开微信"),
 *      @SWG\Property(property="is_weibo_public",type="bool",default=true,description="是否公开微博"),
 *      @SWG\Property(property="site",type="string",default=null,description="个人网站"),
 *      @SWG\Property(property="created_at",type="string",format="date-time",description="创建时间",readOnly=true),
 *      @SWG\Property(property="updated_at",type="string",format="date-time",description="更新时间",readOnly=true)
 * )
 *
 * // Fillable parameters 表单参数
 * @SWG\Parameter(parameter="ProfileForm-title",name="title",in="formData",type="string",maxLength=16,default=null,description="头衔")
 * @SWG\Parameter(parameter="ProfileForm-name",name="name",in="formData",type="string",maxLength=32,default=null,description="真实姓名")
 * @SWG\Parameter(parameter="ProfileForm-gender",name="gender",in="formData",type="string",enum={"male","female","secret"},default="secret",description="性别")
 * @SWG\Parameter(parameter="ProfileForm-idcard",name="idcard",in="formData",type="string",maxLength=18,default=null,description="身份证号")
 * @SWG\Parameter(parameter="ProfileForm-birthday",name="birthday",in="formData",type="string",format="date",default=null,description="生日")
 * @SWG\Parameter(parameter="ProfileForm-city",name="city",in="formData",type="string",maxLength=32,default=null,description="城市")
 * @SWG\Parameter(parameter="ProfileForm-qq",name="qq",in="formData",type="string",maxLength=16,default=null,description="QQ")
 * @SWG\Parameter(parameter="ProfileForm-about",name="about",in="formData",type="string",maxLength=191,default=null,description="跟人介绍")
 * @SWG\Parameter(parameter="ProfileForm-company",name="company",in="formData",type="string",maxLength=32,default=null,description="公司")
 * @SWG\Parameter(parameter="ProfileForm-job",name="job",in="formData",type="string",maxLength=16,default=null,description="职务")
 * @SWG\Parameter(parameter="ProfileForm-school",name="school",in="formData",type="string",maxLength=32,default=null,description="大学")
 * @SWG\Parameter(parameter="ProfileForm-major",name="major",in="formData",type="string",maxLength=16,default=null,description="专业")
 * @SWG\Parameter(parameter="ProfileForm-weibo",name="weibo",in="formData",type="string",maxLength=64,default=null,description="微博")
 * @SWG\Parameter(parameter="ProfileForm-is_qq_public",name="is_qq_public",in="formData",type="boolean",default=true,description="公开QQ")
 * @SWG\Parameter(parameter="ProfileForm-is_weixin_public",name="is_weixin_public",in="formData",type="boolean",default=true,description="公开微信")
 * @SWG\Parameter(parameter="ProfileForm-is_weibo_public",name="is_weibo_public",in="formData",type="boolean",default=true,description="公开微博")
 * @SWG\Parameter(parameter="ProfileForm-site",name="site",in="formData",type="string",maxLength=64,default=null,description="个人网站")
 */
class Profile extends Model
{
    use SortableTrait;

    /**
     * @var string 用户详细信息
     */
    protected $table = 'profile';

    /**
     * @var string
     */
    public $incrementing = false;

    /**
     * @var string
     */
    public $primaryKey = 'user_id';

    /**
     * @var array
     */
    public $sortable = ['*'];

    public $date = ['birthday'];

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'title',
        'name',
        'idcard',
        'gender',
        'birthday',
        'city',
        'qq',
        'about',
        'company',
        'job',
        'school',
        'major',
        'weibo',
        'weixin',
        'is_qq_public',
        'is_weixin_public',
        'is_weibo_public',
        'site',
    ];

    /**
     * @var array
     */
    public static $baseFields = [
        'user_id',
        'title',
        'name',
        'gender',
        'birthday',
        'city',
        'about',
        'company',
        'job',
        'school',
        'major',
        'qq',
        'weibo',
        'weixin',
        'is_qq_public',
        'is_weixin_public',
        'is_weibo_public',
        'site',
    ];
}
