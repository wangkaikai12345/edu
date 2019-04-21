<?php

namespace App\Http\Controllers;

use Dingo\Api\Routing\Helpers;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

// Swagger 基本配置信息
/**
 * @SWG\Swagger(
 *   basePath="/api",
 *   schemes={"http", "https"},
 *   host=L5_SWAGGER_CONST_HOST,
 *   @SWG\Info(
 *     version="1.0.0",
 *     title=L5_SWAGGER_CONST_TITLE,
 *     description=L5_SWAGGER_CONST_DESCRIPTION,
 *     @SWG\Contact(email=L5_SWAGGER_CONST_EMAIL,name=L5_SWAGGER_CONST_NAME)
 *   ),
 *   @SWG\Parameter(name="page",in="query",type="integer",description="当前页",default=1),
 *   @SWG\Parameter(name="per_page",in="query",type="integer",description="分页数量",default=15),
 *   @SWG\Parameter(name="search",in="query",type="string",description=""),
 *   @SWG\Parameter(name="sort",in="query",type="string"),
 * )
 */

// 分页
/**
 * @SWG\Definition(
 *   definition="MetaProperty",
 *   type="object",
 *   description="分页信息",
 *   @SWG\Property(property="per_page",type="integer",minimum=1),
 *   @SWG\Property(property="current_page",type="integer",maximum=100),
 *   @SWG\Property(property="total",type="integer"),
 * )
 */

// 参数异常
/**
 * @SWG\Response(response="ResourceException",description="参数无效",@SWG\Schema(
 *  @SWG\Property(property="status_code",type="integer",example="422"),
 *  @SWG\Property(property="message",type="string",example="Invalid input"),
 *  @SWG\Property(property="errors",type="object",example="{username: ['用户名已存在', '用户名长度不能超过 10 个字符', '用户名被禁用']}")
 * ))
 */

// 身份验证异常
/**
 * @SWG\Response(response="AuthorizationException",description="登录凭证失效",@SWG\Schema(
 *  @SWG\Property(property="status_code",type="integer",example="401"),
 *  @SWG\Property(property="message",type="string",example="需要身份验证。")
 * ))
 */

// 权限验证异常
/**
 * @SWG\Response(response="UnauthorizedException",description="权限验证失败",@SWG\Schema(
 *  @SWG\Property(property="status_code",type="integer",example="403"),
 *  @SWG\Property(property="message",type="string",example="权限不具备，访问被拒绝。")
 * ))
 */

// 禁用异常
/**
 * @SWG\Response(response="UserDisabledException",description="用户已被禁用",@SWG\Schema(
 *  @SWG\Property(property="status_code",type="integer",example="423"),
 *  @SWG\Property(property="message",type="string",example="用户被禁用！")
 * ))
 */

// 基本异常
/**
 * @SWG\Response(response="UnexpectedException",description="服务器繁忙",@SWG\Schema(
 *  @SWG\Property(property="status_code",type="integer",example="500"),
 *  @SWG\Property(property="message",type="string",example="服务器繁忙。")
 * ))
 */

// 搜索参数解析
/**
 * @SWG\Definition(
 *     definition="Searchable",
 *     type="object",
 *     description="[搜索参数解析](https://github.com/jedrzej/searchable)",
 *     @SWG\Property(property="equal",type="string",example="username=admin",description="等式搜索"),
 *     @SWG\Property(property="left_like",type="string",example="username=%admin",description="左模糊搜索，示例含义即搜索以admin结尾的数据"),
 *     @SWG\Property(property="right_like",type="string",example="username=admin%",description="右模糊搜索，示例含义即搜索以admin开头的数据"),
 *     @SWG\Property(property="all_like",type="string",example="username=%admin%",description="全模糊搜索，示例含义即搜索含admin的的数据"),
 *     @SWG\Property(property="gt",type="string",example="created_at=(gt)2015-01-01",description="大于（greater than），示例含义即搜索创建时间大于2015-01-01 00:00:00的数据"),
 *     @SWG\Property(property="ge",type="string",example="created_at=(ge)2016-01-01",description="大于等于（greater than or equal to），示例含义即搜索创建时间大于等于2016-01-01 00:00:00日的数据"),
 *     @SWG\Property(property="lt",type="string",example="created_at=(lt)2017-01-01",description="小于（less than），示例含义即搜索创建时间小于2017-01-01 00:00:00的数据"),
 *     @SWG\Property(property="le",type="string",example="created_at=(le)2018-01-01",description="小于等于（less than or equal to），示例含义即搜索创建时间小于等于2018-01-01 00:00:00日的数据"),
 *     @SWG\Property(property="array",type="string",example="id=1,2,3,4,5",description="数组搜索，即搜索ID为1、2、3、4、5的数据"),
 *     @SWG\Property(property="invert",type="string",example="username=!admin",description="反向搜索，即搜索用户名不为admin的数据"),
 *     @SWG\Property(property="multiple",type="string",example="created_at=[](lt)2018&created_at=[](gt)2017",description="字段多条件搜索，即搜索创建时间大于2017-01-01 00:00:00，同时小于2018-01-01 00:00:00的数据"),
 * )
 */

// 排序参数解析
/**
 * @SWG\Definition(
 *     definition="sortable",
 *     type="object",
 *     description="[排序参数解析](https://github.com/jedrzej/sortable)",
 *     @SWG\Property(property="single",type="string",example="sort=created_at,desc",description="单个排序，示例含义即按创建时间降序排序"),
 *     @SWG\Property(property="multiple",type="string",example="sort[]=group_id,asc&sort[]=user_id,desc",description="多个排序，示例含义即以 group_id 升序排序，当group_id相同时，按user_id降序排序"),
 * )
 */

// 关联加载参数解析
/**
 * @SWG\Definition(
 *     definition="Include",
 *     type="object",
 *     description="关联加载参数解读 [关联参数使用文档](https://fractal.thephpleague.com/transformers/)",
 *     @SWG\Property(property="single",type="string",example="include=user",description="单个关联，示例即关联加载用户信息"),
 *     @SWG\Property(property="multiple",type="string",example="include=user,product",description="多个关联，示例即关联加载用户信息和商品信息"),
 *     @SWG\Property(property="nest",type="string",example="include=user.courses",description="嵌套关联，示例即关联加载用户用户信息，并且关联用户下的课程信息"),
 * )
 */

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    use Helpers;

    /**
     * 初始化最大分页数
     *
     * @param int $perPage 默认单页数据量
     * @param int $maxPerPage 默认最大单页数据量
     * @return array|\Illuminate\Http\Request|string
     */
    static protected function perPage($perPage = 15, $maxPerPage = 100)
    {
        $perPage = request('per_page', $perPage);
        return  $perPage < $maxPerPage ? $perPage : $maxPerPage;
    }

    /**
     * 设置排序默认排序为倒序
     *
     * @param string $sort
     * @return void
     */
    static protected function orderBy($sort = 'created_at,desc')
    {
        if (!request()->has('sort')) {
            request()->offsetSet('sort',$sort);
        }
    }
}
