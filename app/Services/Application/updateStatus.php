<?php

namespace App\Services\Application;

use App\Enums\TypeApplication;
use App\Models\application;
use App\Models\dayoff;
use App\Models\employee;
use App\Models\notification;
use App\Services\Notification\pushNotificationSpecific;
use App\Services\Notification\PushNotificationSpecificVer2;
use App\Services\ParseToken\ParseToken;
use App\Supports\Responder;
use Carbon\Carbon;
use Exception;

class updateStatus
{
    private $parseToken;
    private $pushNotificationSpecific;
    private $pushNotificationSpecificVer2;

    public function __construct(
        ParseToken $parseToken,
        pushNotificationSpecific $pushNotificationSpecific,
        PushNotificationSpecificVer2 $pushNotificationSpecificVer2,
    ) {
        $this->parseToken = $parseToken;
        $this->pushNotificationSpecific = $pushNotificationSpecific;
        $this->pushNotificationSpecificVer2 = $pushNotificationSpecificVer2;
    }

    public function handle($request)
    {
        try {
            $employee = $this->parseToken->handle();
            $status = $request->status;
            $now = Carbon::now()->timestamp * 1000;

            if (!$employee) {
                return Responder::fail(null, 'Bạn chưa đăng nhập');
            }

            $application = application::where('id', $request->id)->update([
                'status' => $status,
                'approverId' => $employee->id,
                'updatedAt' => $now
            ]);

            $application = application::where('id', $request->id)->first();

            $noti = notification::create([
                'receiverId' => $application->employeeId,
                'senderId' => $employee->id,
                'applicationId' => $application->id,
                'seen' => 0,
                'createdAt' => $now,
            ]);

            switch ($application->status) {
                case 0:
                    // Trường hợp từ chối đơn nghỉ phép có lương
                    if ($application->type == TypeApplication::PaidLeave) {
                        $allDayoff = dayoff::where('applicationId', $application->id)->where('session', 0)->count(); // Nghỉ cả ngày
                        $halfDayoff = dayoff::where('applicationId', $application->id)->where('session', '!=', 0)->count(); // Nghỉ nửa buổi

                        $dayoffleft = employee::where('id', $application->employeeId)->pluck('dayOff')->first();
                        $newDayoffleft = $dayoffleft + $allDayoff + ($halfDayoff / 2);
                        employee::where('id', $application->employeeId)->update(['dayOff' => $newDayoffleft]);
                    }

                    $text = $employee->fullname . " từ chối " . $application->title . " của bạn.";
                    break;
                case 1:
                    $text = $employee->fullname . " đã duyệt " . $application->title . " của bạn.";
                    break;
                default:
                    return Responder::fail(null, 'Đơn không hợp lệ');
            }

            $deviceTokens = employee::where('id', $application->employeeId)
                ->pluck('device_token')
                ->toArray();

            $type_noti = "application";
            $id_noti = $application->id;
            try {
                $this->pushNotificationSpecificVer2->sendNotification($deviceTokens, $application->title, $text, $type_noti, $id_noti);
            }catch(Exception $e) {
            }
            return Responder::success($application, 'Cập nhật thành công');
        } catch (Exception $e) {
            return Responder::fail(null, $e->getMessage());
        }
    }
}
