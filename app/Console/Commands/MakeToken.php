<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class makeToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zxy:token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To make a constant jwt token for developers（为开发者提供一个永久的 JWT Token）';

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
        $id = $this->ask('Please input a user id（请指定一个用户 ID）');

        $user = User::findOrFail($id);

        // Minute
        $token = auth()->setTTL(60 * 24 * 365)->fromUser($user);

        $this->info($token);
    }
}
