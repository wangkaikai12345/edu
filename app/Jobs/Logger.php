<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class Logger implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $data;

    // 需要隐藏的字段
    protected $hiddenKeys = [
        'password',
        'password_confirmation',
        'old_password',
        'new_password',
        'new_password_confirmation'
    ];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            // 对需要隐藏的字段做特殊处理
            foreach ($this->hiddenKeys as $key) {
                if (isset($this->data['params'][$key])) {
                    $this->data['params'][$key] = '*****';
                }
            }

            \App\Models\Log::create($this->data);
        } catch (\Exception $e) {
            \Log::error('日志信息保存失败');
            \Log::error($e->getMessage());
        }
    }
}
