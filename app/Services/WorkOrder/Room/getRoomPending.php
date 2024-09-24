<?php

namespace App\Services\WorkOrder\Room;

use App\Enums\ParticipantStatusEnum;
use App\Enums\TypeNotification;
use App\Models\employee;
use App\Models\notification;
use App\Models\participant;
use App\Models\room;
use App\Services\ParseToken\ParseToken;
use App\Supports\Responder;

class getRoomPending
{
    private $parseToken;

    public function __construct(
        ParseToken $parseToken,
    ) {
        $this->parseToken = $parseToken;
    }

    public function getRoomPending($request)
    {
        $employee = $this->parseToken->handle();
        $perPage = 10;
        $offset = ($request->page) * $perPage;

        $listRoomID = participant::where('employeeId', $employee->id)->where('status', ParticipantStatusEnum::Pending)
            ->orderBy('updatedAt', 'asc')
            ->pluck('roomId')
            ->skip($offset)
            ->take($perPage)
            ->toArray();

        // lấy danh sách rooms
        $rooms = [];
        foreach ($listRoomID as $roomId) {
            $roomData = [];
            $room = room::where('roomId', $roomId)->first();

            // Lấy danh sách những người tham gia của từng phòng
            $participants = Participant::where('roomId', $roomId)->pluck('employeeId')->toArray();
            $participantList = Employee::whereIn('id', $participants)->get();

            // Thêm danh sách người tham gia vào phòng
            $roomData['room'] = $room->toArray();
            $roomData['room']['participants'] = $participantList->toArray();

            //Lấy thời gian nhận lời mời tham gia phòng
            $create_at = notification::where('applicationId', $roomId)->where('type', TypeNotification::WorkOrder)->pluck('createdAt')->first();
            $roomData['room']['create_at'] = $create_at;

            $rooms[] = $roomData;
        }

        // Sắp xếp lại danh sách rooms theo updatedAt giảm dần
        usort($rooms, function ($a, $b) {
            return $b['room']['updatedAt'] <=> $a['room']['updatedAt'];
        });

        return Responder::success($rooms, 'Danh sách phòng chưa duyệt');
    }
}
