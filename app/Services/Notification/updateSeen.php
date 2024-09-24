<?php

namespace App\Services\Notification;

use App\Models\notification;
use App\Supports\Responder;
use Exception;

class updateSeen
{
    public function handle($request)
    {
        try {
            $notification = notification::where('id', '=', $request->id)
                ->where('receiverId', '=', $request->receiverId)
                ->update(['seen' => 1]);

            if (!$notification) {
                return Responder::fail(null, 'Cập nhật thông báo thất bại', 404);
            }

            return Responder::success($notification, 'Cập nhật thông báo thành công');
        } catch (Exception $e) {
            return Responder::fail(null, $e->getMessage());
        }
    }
}
