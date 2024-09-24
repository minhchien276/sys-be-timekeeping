<?php

namespace App\Http\Controllers;

use App\Jobs\PushNotificationJob;
use App\Models\employee;
use App\Services\Notification\getApprove;
use App\Services\Notification\getNotification;
use App\Services\Notification\getNotificationDetails;
use App\Services\Notification\updateSeen;
use App\Supports\Responder;
use Exception;
use Illuminate\Http\Request;

class ApiNotificationController extends Controller
{
    private $getNotification;
    private $getApprove;
    private $updateSeen;
    private $getNotificationDetails;

    public function __construct(
        getNotification $getNotification,
        getApprove $getApprove,
        updateSeen $updateSeen,
        getNotificationDetails $getNotificationDetails,
    ) {
        $this->middleware('auth:api');
        $this->getNotification = $getNotification;
        $this->getApprove = $getApprove;
        $this->getNotificationDetails = $getNotificationDetails;
        $this->updateSeen = $updateSeen;
    }

    public function updateDeviceToken(Request $request, $id)
    {
        try {
            $employee = null;
            if ($request->device_token != null) {
                $employee = employee::where('id', $id)->update(['device_token' => $request->device_token]);
            }

            return Responder::success($employee, 'Update device token successfully');
        } catch (Exception $e) {
            return Responder::success(null, 'Update device token error');
        }
    }

    public function pushNoti()
    {
        $deviceTokens = employee::where('device_token', '<>', null)
            ->pluck('device_token')
            ->toArray();

        PushNotificationJob::dispatch('sendBatchNotification', [
            $deviceTokens,
            [
                'topicName' => 'members',
                'title' => 'Kiểm Tra Đồng Phục',
                'body' => 'Chuẩn bị trước 50k nếu bạn không mặc nhé!',
            ],
        ]);
    }

    public function getNotification(Request $request)
    {
        return $this->getNotification->handle($request);
    }

    public function getApprove(Request $request)
    {
        return $this->getApprove->handle($request);
    }

    public function updateSeen(Request $request)
    {
        return $this->updateSeen->handle($request);
    }

    public function getNotificationDetails($id)
    {
        return $this->getNotificationDetails->handle($id);
    }
}
