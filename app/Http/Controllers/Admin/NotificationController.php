<?php

namespace App\Http\Controllers\Admin;

use App\Enums\TypeNotification;
use App\Http\Controllers\Controller;
use App\Models\notification;
use App\Services\Notification\PushNotificationVer2;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    private $pushNotificationVer2;

    public function __construct(
        PushNotificationVer2 $pushNotificationVer2,
    ) {
        $this->pushNotificationVer2 = $pushNotificationVer2;
    }

    public function index()
    {
        return view('admin.notification.index');
    }

    public function pushNotificationToAll(Request $request)
    {
        try {
            // $deviceTokens = employee::where('device_token', '<>', null)
            //     ->pluck('device_token')
            //     ->toArray();

            // PushNotificationJob::dispatch('sendBatchNotification', [
            //     $deviceTokens,
            //     [
            //         'topicName' => 'members',
            //         'title' => $request->title,
            //         'body' => $request->content,
            //     ],
            // ]);

            DB::beginTransaction();
            $notification = notification::create([
                'notiTitle' => $request->title,
                'notiContent' => $request->content,
                'receiverId' => 0,
                'senderId' => $request->session()->get('user')->id,
                'applicationId' => null,
                'type' => TypeNotification::Normal,
                'seen' => 1,
                'createdAt' => Carbon::now()->timestamp * 1000,
            ]);

            $topic = "members-v2";
            $title = $request->title;
            $body = $request->content;
            $type_noti = 'normal';
            $id_noti = $notification->id;

            $this->pushNotificationVer2->sendNotification($topic, $title, $body, $type_noti, $id_noti);
            DB::commit();

            return redirect()->back()->with('success', 'Gửi thông báo thành công!');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gửi thông báo không thành công!');
        }
    }
}
