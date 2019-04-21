## 关于智校云
一个在线教育平台

## 项目搭建
1. 安装依赖
    ```
    composer install
    ```
2. 复制 env 文件，配置必要参数
3. 生成 app_key
    ```
    php artisan key:generate
    ```
4. 生成 jwt_secret
    ```
    php artisan jwt:secret
    ```
5. 执行迁移文件，并执行种子生成器（批量生成假数据）
    ```
    php artisan migrate --seed
    ```
    
6. 微信支付退款需要证书，请登录微信管理后台下载证书。证书路径为:
    ``
    storage/cert/apiclient_cert.pem
    storage/cert/apiclient_key.pem 
    ```
    
## 全局函数
1. 生成 Dingo 的命名路由
    ```
    /**
     * 用于生成 Dingo 的命名路由
     *
     * @param $name 命名路由
     * @param array $params 参数
     * @param string $version 版本
     * @param bool $absolute 是否为绝对路径，默认为 true。
     *
     * @return \Dingo\Api\Routing\UrlGenerator
     */
     fucntion dingo_route(string $name, array $params, string $version, bool $absolute) : string
    ```
    > 当使用此函数时，并且 $absolute 为 true 时，获取的是绝对路径，此时会拼接 env 文件中的 APP_URL 变量，如果你遇到这个问题，请修改变量为你自己的配置即可。

## Artisan 命令
1. 用于生成一个 JWT Token，旨在为开发者提供测试环境，有效期 1 年
    ```
    php artisan zxy:token
    ```

## 目录结构
采用 `Laravel` 默认目录结构。

## 建议
1. 强烈推荐你使用`类型约束`去开发，比如 `dingo_route` 全局函数，对参数进行类型约束，对返回值进行类型约束。
2. `约定大于配置`，推荐使用 `Laravel` 默认推荐的方式去开发。比如类的方法的注释、配置文件注释等等。

## 响应码规定
1. 使用默认的 http status code 来标识请求状态
2. 使用子码 status_code 来标识具体业务异常信息（请看附录响应码约定）

## 附录：响应码

|status code|desc|
|:----:|:----:|
|401|需要登录才能访问|
|403|Forbidden，禁止访问|
|404|数据不存在|
|405|Bad Request错误的请求|
|422|参数错误|
|429|频率限制|


## 路由搜索条件
### 后端

```php
composer require "jedrzej/searchable" "0.0.14"
```
在需要使用搜索功能的 model 中，使用如下：
```php
use Jedrzej\Searchable\SearchableTrait;

class User extends Eloquent
{
	use SearchableTrait;
	
	// 可搜索的字段。
	public $searchable = ['username','nickname', 'created_at','profile:gender'];
	
	// 或者使用此方法设置可搜索的字段。与 $searchable  二选一即可
	public function getSearchableAttributes()
	{
	    return ['username','nickname', 'created_at','profile:gender'];;
	}
}
```
如果所有字段均可以搜索，使用 ` public $searchable = ['*']; `

controller 调用如下：
```php
// 没有参数，默认调用了 Input::all()
User::filtered()->get();

// 或者限定参数
User::filtered($request->only(['username','nickname'])->get();
```

### 前端 api 调用

直接在路由后面拼接参数即可，拼接格式如下

| url | sql 语句 | 备注 | 
| ---------------|----------------|----------------|
|?username=admin| where `username`='admin' | 等于|
|?username=admin1&username=admin2| where `username`='admin2' | 错误写法（同一个字段有多个搜索条件，这种写法 只会取最后一个）|
|?username=[]admin1&username[]=admin2| where `username`='admin1' and `username`='admin2' |正确写法（ 同一个字段有多个搜索条件）|
|?created_at[]=20%&created_at[]=!2013%| where `created_at` like '20%' and `created_at` not like '2013%'  | |
|?username=1,2| where `username` in ('1','2') |  |
|?username=ad% | where `username` like 'ad%'  | 模糊匹配|
|?username=%n| where `username` like '%n' |模糊匹配|
|?username=(null)| where `username` is null | 为空 |
|?created_at=(ge)2015-01-01|where `created_at` >= '2015-01-01'  |大于等于，详见下方 比较操作符|
|?username=!admin|where `username` <> 'admin1'  | 取反|
|?username=!admin1,admin2|where `username` not in ('admin1', 'admin2') | |
|?created_at=!(ge)2015| where `created_at` < '2015'  | |
|?username=!(null) | where `username` is not null  | |
|?profile:gender=male | where exists (select * from `user_profile` where `users`.`id` = `user_profile`.`user_id` and `gender` = 'male') |关联表查询|   

**比较操作符**
	
|  url 中 | 含义 |汉语|
| ---------------|----------------|----------------|
| gt  |greater than  |大于|
| ge  |greater than or equal  |大于等于|
| lt  |les than or equal  |小于|
| le  |les than or equal  |小于等于|

## 接口文档 
采用 [swagger](https://github.com/DarkaOnLine/L5-Swagger) Package 进行管理。

### URL：`/api-docs`
请用浏览器访问 URL，即可看到 Swagger UI 下的文档信息

### 开发
- Swagger 基本配置信息在 `app\Http\Controllers\Controller.php` 中
- 请在 Controller 的 function 的注释上编写接口文档，具体语法格式，请看[文档](http://zircote.com/swagger-php/)，或者 package 的源码中的 Example。
- 在开发完成后，请使用 `php artisan l5-swagger:generate` 重新生成接口文档。

### 定时任务
请在服务器上添加定时任务，`crontab -e`
```sh
* * * * * php /path-to-your-project/artisan schedule:run >> /dev/null 2>&1
```
> 1. 当任务不执行时，尝试在 `/var/spool/cron/crontabs` 下用户目录下添加
