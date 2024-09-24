<?php

namespace App\Services\Notification;

use App\Models\notification;
use App\Supports\Responder;
use Exception;

class getApprove
{
    public function handle($request)
    {
        try {
            $notification = notification::leftJoin('application', 'notification.applicationId', '=', 'application.id')
                ->select('application.*', 'notification.seen')
                ->where('notification.receiverId', '=', $request->receiverId)
                ->whereNull('application.approverId')
                ->get();

            if (!$notification) {
                return Responder::fail(null, 'Không tìm thấy đơn cần duyệt', 404);
            }

            return Responder::success($notification, 'Lấy đơn cần duyệt thành công');
        } catch (Exception $e) {
            return Responder::fail(null, $e->getMessage());
        }
    }
}
