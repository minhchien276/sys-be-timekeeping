<?php

namespace App\Services\Application;

use App\Enums\RoleEnum;
use App\Enums\TypeApplication;
use App\Models\application;
use App\Models\dayoff;
use App\Models\early_late;
use App\Models\employee;
use App\Models\notification;
use App\Models\overtime;
use App\Services\ListDayOfDate\getListDayOfDate;
use App\Services\Notification\pushNotificationSpecific;
use App\Services\Notification\PushNotificationSpecificVer2;
use App\Supports\Responder;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Validator;

class createApplication
{
    private $pushNotificationSpecific;
    private $getListDayOfDate;
    private $pushNotificationSpecificVer2;

    public function __construct(
        pushNotificationSpecific $pushNotificationSpecific,
        PushNotificationSpecificVer2 $pushNotificationSpecificVer2,
        getListDayOfDate $getListDayOfDate
    ) {
        $this->pushNotificationSpecific = $pushNotificationSpecific;
        $this->pushNotificationSpecificVer2 = $pushNotificationSpecificVer2;
        $this->getListDayOfDate = $getListDayOfDate;
    }

    public function handle($request)
    {
        $now = Carbon::now()->timestamp * 1000;

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'employeeId' => 'required',
        ], [
            'title.required' => 'Tiêu đề là bắt buộc.',
            'employeeId.required' => 'Mã nhân viên là bắt buộc.',
        ]);

        if ($validator->fails()) {
            return Responder::fail(null, $validator->errors()->first(), 400);
        }

        try {
            $requestData = $request->all();

            $data = $requestData['data'];
            $employeeId = $requestData['employeeId'];
            $type_application = $requestData['type_application'];
            $title = $requestData['title'];
            $content = $requestData['content'];
            $receiverId = $requestData['receiverId'];

            $text = "";
            $senderName = employee::where('id', $employeeId)->pluck('fullname')->first();

            if ($receiverId == null || $receiverId == []) {
                return Responder::fail(null, 'Bạn chưa chọn người nhận đơn!');
            }

            $application = application::create([
                'title' => $title,
                'content' => $content,
                'type' => $type_application,
                'employeeId' => $employeeId,
                'createdAt' => $now,
            ]);

            foreach ($data as $item) {
                $dayOff = employee::where('id', $employeeId)->pluck('dayOff')->first();
                switch ($type_application) {
                    case TypeApplication::PaidLeave:
                        if ($item['session'] == 0) {
                            $dayOffLeft = $dayOff - 1;
                        } else {
                            $dayOffLeft = $dayOff - 0.5;
                        }

                        if ($dayOffLeft < 0) {
                            return Responder::fail(null, '. Số ngày nghỉ phép của bạn không đủ');
                        }
                        employee::where('id', $employeeId)->update(['dayOff' => $dayOffLeft]);

                        dayoff::create([
                            'employeeId' => $employeeId,
                            'applicationId' => $application->id,
                            'dayOffDate' => $item['dayOffDate'],
                            'session' => $item['session'],
                            'type' => $type_application,
                            'createdAt' => $now,
                        ]);

                        $text = $senderName . " đã gửi " . $title . " đến bạn.";
                        break;
                    case TypeApplication::UnpaidLeave:
                        dayoff::create([
                            'employeeId' => $employeeId,
                            'applicationId' => $application->id,
                            'dayOffDate' => $item['dayOffDate'],
                            'session' => $item['session'],
                            'type' => $type_application,
                            'createdAt' => $now,
                        ]);

                        $text = $senderName . " đã gửi " . $title . " đến bạn.";
                        break;
                    case TypeApplication::OverTime:
                        overtime::create([
                            'employeeId' => $employeeId,
                            'applicationId' => $application->id,
                            'startTime' =>  $item['startTime'],
                            'endTime' =>  $item['endTime'],
                            'dayOffDate' => $item['dayOffDate'],
                            'hours' =>  $item['hours'],
                            'createdAt' => $now,
                        ]);

                        $leader = employee::where('id', $employeeId)->pluck('roleId')->first();

                        if ($leader == RoleEnum::Leader) {
                            $application->status = 1;
                            $application->approverId = $employeeId;
                            $application->updatedAt = $now;
                            $application->save();
                            return Responder::success($application, 'Tạo đơn thành công');
                        }

                        $text = $senderName . " đã gửi " . $title . " đến bạn.";
                        break;
                    case TypeApplication::Lated:
                    case TypeApplication::Early:
                        $currentDate = $this->getListDayOfDate->handle();

                        // Tính số lần đi muộn về sớm trong tháng
                        $countEarlyLated = early_late::where('employeeId', $employeeId)->whereBetween('dayOffDate', $currentDate)->count();

                        if ($countEarlyLated >= 3) {
                            return Responder::fail(null, 'Bạn đã xin hết lần đi muộn về sớm trong tháng.');
                        } else {
                            early_late::create([
                                'employeeId' => $employeeId,
                                'applicationId' => $application->id,
                                'hours' =>  $item['hours'],
                                'type' => $type_application,
                                'dayOffDate' => $item['dayOffDate'],
                                'createdAt' => $now,
                            ]);

                            $text = $senderName . " đã gửi " . $title . " đến bạn.";
                            break;
                        }
                    default:
                        return Responder::fail(null, 'Đơn không hợp lệ');
                }
            }

            foreach ($receiverId as $id) {
                $employee = Employee::find($id);
                if ($employee) {
                    notification::create([
                        'receiverId' => $id,
                        'senderId' => $employeeId,
                        'applicationId' => $application->id,
                        'seen' => 0,
                        'createdAt' => $now,
                    ]);
                }
            }

            $deviceTokens = employee::whereIn('id', $receiverId)
                ->pluck('device_token')
                ->toArray();

            $type_noti = "application";
            $id_noti = $application->id;

            $this->pushNotificationSpecificVer2->sendNotification($deviceTokens, $title, $text, $type_noti, $id_noti);

            return Responder::success($application, 'Tạo đơn thành công');
        } catch (Exception $e) {
            return Responder::fail(null, $e->getMessage(), 400);
        }
    }
}
