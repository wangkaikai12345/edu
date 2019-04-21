<?php

namespace App\Console\Commands;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Refund;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateYdmaUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:ydma-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '迁移一版源代码用户。';

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
        $users = DB::connection('ydma')->table('data_info_user')->whereRaw("tel regexp '^[1][0-9]{10}$'")->orderBy('add_time', 'desc')->groupBy('tel')->get();

        foreach ($users as $user) {
            $job = new \App\Jobs\MigrateOldYdmaJob($user);
            dispatch($job)->onQueue('MigrateOldYdmaUser');
        }
    }
}
