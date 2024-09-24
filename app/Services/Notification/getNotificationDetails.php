<?php

namespace App\Services\Notification;

use App\Models\notification;
use App\Supports\Responder;
use Exception;

class getNotificationDetails
{
    public function handle($id)
    {
        try {
            $notification = notification::where('id', $id)->first();

            if (!$notification) {
                return Responder::fail(null, 'Không tìm thấy đơn cần duyệt', 404);
            }

            return Responder::success($notification, 'Lấy đơn cần duyệt thành công');
        } catch (Exception $e) {
            return Responder::fail(null, $e->getMessage());
        }
    }
}
