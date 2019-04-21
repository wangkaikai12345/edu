<?php

namespace App\Console\Commands;

use function foo\func;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DataMigrateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->line('开始数据迁移!');
        // 旧数据库的连接, 并以此查询需要迁移的表
        $oldMysql = DB::connection('ydma_cn');

        // audio
        $datas = $oldMysql->table('audio')->get();
        $datas->each(function ($data) {
            $job = new \App\Jobs\DataMigrate('audio', $data);
            dispatch($job)->onQueue('DataMigrate');
        });

        // chapters
        $datas = $oldMysql->table('chapters')->get();
        $datas->each(function ($data) {
            dispatch(new \App\Jobs\DataMigrate('chapters', $data))->onQueue('DataMigrate');
        });

        // coupons
        $datas = $oldMysql->table('coupons')->get();
        $datas->each(function ($data) {
            dispatch(new \App\Jobs\DataMigrate('coupons', $data))->onQueue('DataMigrate');
        });

        // courses
        $datas = $oldMysql->table('courses')->get();
        $datas->each(function ($data) {
            dispatch(new \App\Jobs\DataMigrate('courses', $data))->onQueue('DataMigrate');
        });

        // docs
        $datas = $oldMysql->table('docs')->get();
        $datas->each(function ($data) {
            dispatch(new \App\Jobs\DataMigrate('docs', $data))->onQueue('DataMigrate');
        });

        // favorites
        $datas = $oldMysql->table('favorites')->get();
        $datas->each(function ($data) {
            dispatch(new \App\Jobs\DataMigrate('favorites', $data))->onQueue('DataMigrate');
        });

        // follows
        $datas = $oldMysql->table('follows')->get();
        $datas->each(function ($data) {
            dispatch(new \App\Jobs\DataMigrate('follows', $data))->onQueue('DataMigrate');
        });

        // notes
        $datas = $oldMysql->table('notes')->get();
        $datas->each(function ($data) {
            dispatch(new \App\Jobs\DataMigrate('notes', $data))->onQueue('DataMigrate');
        });

        // orders
        $datas = $oldMysql->table('orders')->get();
        $datas->each(function ($data) {
            dispatch(new \App\Jobs\DataMigrate('orders', $data))->onQueue('DataMigrate');
        });

        // plan_members
        $datas = $oldMysql->table('plan_members')->get();
        $datas->each(function ($data) {
            dispatch(new \App\Jobs\DataMigrate('plan_members', $data))->onQueue('DataMigrate');
        });

        // plan_teachers
        $datas = $oldMysql->table('plan_teachers')->get();
        $datas->each(function ($data) {
            dispatch(new \App\Jobs\DataMigrate('plan_teachers', $data))->onQueue('DataMigrate');
        });

        // plans
        $datas = $oldMysql->table('plans')->get();
        $datas->each(function ($data) {
            dispatch(new \App\Jobs\DataMigrate('plans', $data))->onQueue('DataMigrate');
        });

        // ppts
        $datas = $oldMysql->table('ppts')->get();
        $datas->each(function ($data) {
            dispatch(new \App\Jobs\DataMigrate('ppts', $data))->onQueue('DataMigrate');
        });

        // profile
        $datas = $oldMysql->table('profile')->get();
        $datas->each(function ($data) {
            dispatch(new \App\Jobs\DataMigrate('profile', $data))->onQueue('DataMigrate');
        });

        // refunds
        $datas = $oldMysql->table('refunds')->get();
        $datas->each(function ($data) {
            dispatch(new \App\Jobs\DataMigrate('refunds', $data))->onQueue('DataMigrate');
        });

        // roles
        $datas = $oldMysql->table('roles')->get();
        $datas->each(function ($data) {
            dispatch(new \App\Jobs\DataMigrate('roles', $data))->onQueue('DataMigrate');
        });

        // slides
        $datas = $oldMysql->table('slides')->get();
        $datas->each(function ($data) {
            dispatch(new \App\Jobs\DataMigrate('slides', $data))->onQueue('DataMigrate');
        });

        // task_results
        $datas = $oldMysql->table('task_results')->get();
        $datas->each(function ($data) {
            dispatch(new \App\Jobs\DataMigrate('task_results', $data))->onQueue('DataMigrate');
        });

        // tasks
        $datas = $oldMysql->table('tasks')->get();
        $datas->each(function ($data) {
            dispatch(new \App\Jobs\DataMigrate('tasks', $data))->onQueue('DataMigrate');
        });

        // texts
        $datas = $oldMysql->table('texts')->get();
        $datas->each(function ($data) {
            dispatch(new \App\Jobs\DataMigrate('texts', $data))->onQueue('DataMigrate');
        });

        // topics
        $datas = $oldMysql->table('topics')->get();
        $datas->each(function ($data) {
            dispatch(new \App\Jobs\DataMigrate('topics', $data))->onQueue('DataMigrate');
        });

        // trades
        $datas = $oldMysql->table('trades')->get();
        $datas->each(function ($data) {
            dispatch(new \App\Jobs\DataMigrate('trades', $data))->onQueue('DataMigrate');
        });

        // users
        $datas = $oldMysql->table('users')->get();
        $datas->each(function ($data) {
            dispatch(new \App\Jobs\DataMigrate('users', $data))->onQueue('DataMigrate');
        });

        // videos
        $datas = $oldMysql->table('videos')->get();
        $datas->each(function ($data) {
            dispatch(new \App\Jobs\DataMigrate('videos', $data))->onQueue('DataMigrate');
        });

        // settings
        $datas = $oldMysql->table('settings')->get();
        $datas->each(function ($data) {
            dispatch(new \App\Jobs\DataMigrate('settings', $data))->onQueue('DataMigrate');
        });

        $this->line('数据迁移结束!');
    }
}
