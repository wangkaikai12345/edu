<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestTaskScheduling extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:task-scheduling';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '用于测试定时任务。';

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
        \Log::debug('定时任务测试：'.__Class__);
    }
}
