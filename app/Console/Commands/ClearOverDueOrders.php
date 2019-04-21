<?php

namespace App\Console\Commands;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Refund;
use Illuminate\Console\Command;

class ClearOverDueOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:overdue-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '关闭已过期的购买订单、退款订单。';

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
        $expired = now()->subMinutes(Order::$expires);

        Order::where('status', OrderStatus::CREATED)->where('created_at', '<', $expired)->update([
            'status' => OrderStatus::CLOSED,
            'closed_message' => '超过' . Order::$expires . '分钟，系统自动进行关闭',
            'closed_user_id' => 0
        ]);
    }
}
