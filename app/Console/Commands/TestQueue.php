<?php

namespace App\Console\Commands;

use App\Jobs\DebugLog;
use Illuminate\Console\Command;

class TestQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:queue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '队列测试，当队列执行成功后将在日志中写入一条信息。';

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
     * @return void
     */
    public function handle()
    {
        DebugLog::dispatch();

        $this->info('队列发送成功，请去日志中查看是否有 debug 信息。');
    }
}
