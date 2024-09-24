<?php

namespace App\Services\WorkOrder\Room;

use App\Enums\ParticipantStatusEnum;
use App\Enums\RoleEnum;
use App\Enums\TypeNotification;
use App\Models\employee;
use App\Models\notification;
use App\Models\participant;
use App\Models\room;
use App\Services\Notification\PushNotificationSpecificVer2;
use App\Services\ParseToken\ParseToken;
use App\Supports\Responder;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class createRoom
{
    private $parseToken;
    private $pushNotificationSpecificVer2;

    public function __construct(
        ParseToken $parseToken,
        PushNotificationSpecificVer2 $pushNotificationSpecificVer2,
    ) {
        $this->parseToken = $parseToken;
        $this->pushNotificationSpecificVer2 = $pushNotificationSpecificVer2;
    }

    public function handle($request)
    {
        try {
            $employee = $this->parseToken->handle();
            $now = Carbon::now()->timestamp * 1000;

            try {
                DB::beginTransaction();
                $room = Room::create([
                    "employeeId" => $employee->id,
                    "name" => $request->name,
                    "createdAt" => $now,
                    "updatedAt" => $now,
                ]);

                if (!$room) {
                    DB::rollBack();
                    return Responder::fail(null, 'Tạo phòng không thành công');
                }

                participant::create([
                    "employeeId" => $employee->id,
                    "name" => $request->name,
                    "status" => ParticipantStatusEnum::Approved,
                    "roomId" => $room->roomId,
                    "createdAt" => $now,
                    "updatedAt" => $now,
                ]);

                $participants = [];

                foreach ($request->participantId as $item) {
                    $participant = Participant::create([
                        "employeeId" => $item,
                        "name" => $request->name,
                        "status" => ParticipantStatusEnum::Pending,
                        "roomId" => $room->roomId,
                        "createdAt" => $now,
                        "updatedAt" => $now,
                    ]);

                    if (!$participant) {
                        throw new Exception('Thêm nhân sự không thành công');
                    }

                    $title = "THÔNG BÁO";
                    $body = $employee->fullname . " đã mời bạn tham gia nhóm " .  $request->name;

                    $notification = notification::create([
                        'notiTitle' => $title,
                        'notiContent' => $body,
                        'receiverId' => $item,
                        'senderId' => $employee->id,
                        'applicationId' => $room->roomId,
                        'type' => TypeNotification::WorkOrder,
                        'seen' => 1,
                        'createdAt' => $now,
                    ]);

                    $type_noti = 'work-order';
                    $id_noti = $room->roomId;

                    $participants[] = $participant;
                }

                DB::commit();
                $device_token = employee::whereIn('id', $request->participantId)->pluck('device_token')->toArray();

                $this->pushNotificationSpecificVer2->sendNotification($device_token, $title, $body, $type_noti, $id_noti);
            } catch (Exception $e) {
                DB::rollBack();
                return Responder::fail(null, $e->getMessage());
            }
        } catch (Exception $e) {
            return Responder::fail(null, 'Tạo phòng không thành công');
        } finally {
            return Responder::success($room, 'Tạo phòng thành công');
        };
    }
}
