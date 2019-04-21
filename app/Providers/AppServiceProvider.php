<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Illuminate\Support\Facades\Schema::defaultStringLength(191);

        // if (app()->environment() === 'local') {
        //     \DB::listen(function ($query) {
        //         \Log::info($query->sql, $query->bindings);
        //     });
        // }

        // \Log::info('============ URL: '.request()->fullUrl().' ===============');
//         \DB::listen(function (\Illuminate\Database\Events\QueryExecuted $query) {
//             $sqlWithPlaceholders = str_replace(['%', '?'], ['%%', '%s'], $query->sql);
//
//             $bindings = $query->connection->prepareBindings($query->bindings);
//             $pdo = $query->connection->getPdo();
//
//             \Log::info(vsprintf($sqlWithPlaceholders, array_map([$pdo, 'quote'], $bindings)));
//         });

        \Carbon\Carbon::setLocale('zh');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // 默认 dingo 将 laravel 默认异常的状态码设置为 500，故手动捕获修改。
        \API::error(function (\Illuminate\Auth\AuthenticationException $exception) {
            abort(401, __('auth.401'));
        });

        \API::error(function (\Illuminate\Auth\Access\AuthorizationException $exception) {
            abort(401, __('auth.401'));
        });

        \API::error(function (\Spatie\Permission\Exceptions\UnauthorizedException $exception) {
            abort(403, __('auth.403'));
        });

        \API::error(function (\Illuminate\Database\Eloquent\ModelNotFoundException $exception) {
            abort(404, __(':model not found.', [
                'model' => __('models.' . $exception->getModel())
            ]));
        });

        \API::error(function (\Illuminate\Validation\ValidationException $exception) {
            throw new \Dingo\Api\Exception\ResourceException(__('auth.422'), $exception->errors(), null, [], 422);
        });
    }
}
