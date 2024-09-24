<?php

namespace App\Console\Commands;

use App\Jobs\PushNotificationJob;
use App\Models\employee;
use Illuminate\Console\Command;

class NotificaionUniformCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:notificaion-uniform-cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $deviceTokens = employee::where('device_token', '<>', null)
            ->pluck('device_token')
            ->toArray();

        PushNotificationJob::dispatch('sendBatchNotification', [
            $deviceTokens,
            [
                'topicName' => 'members',
                'title' => 'Nhắc nhở',
                'body' => 'Bạn hãy nhớ chấm công trước khi ra về nhé!',
            ],
        ]);
    }
}
