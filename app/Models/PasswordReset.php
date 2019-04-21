<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *      definition="PasswordReset",
 *      type="object",
 *      required={},
 *      description="密码修改模型",
 *      @SWG\Property(property="email",type="string",readOnly=true),
 *      @SWG\Property(property="token",type="string"),
 *      @SWG\Property(property="created_at",type="string",format="date-time",description="创建时间",readOnly=true)
 * )
 * @SWG\Response(
 *      response="PasswordResetPagination",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/PasswordReset")),
 *          @SWG\Property(property="meta",ref="$/definitions/MetaProperty")
 *      )
 * )
 * @SWG\Response(
 *      response="PasswordResetResponse",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/PasswordReset"))
 *      )
 * )
 */
class PasswordReset extends Model
{
    /**
     * @var string 邮箱密码重置表
     */
    protected $table = 'password_resets';

    /**
     * @var array
     */
    protected $fillable = ['email', 'token', 'created_at'];

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var string
     */
    public $primaryKey = 'email';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    public $dates = ['created_at'];
}
