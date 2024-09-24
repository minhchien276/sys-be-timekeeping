<?php

namespace App\Services\WorkOrder\Room;

use App\Enums\ParticipantStatusEnum;
use App\Enums\StatusTaskEnum;
use App\Models\employee;
use App\Models\participant;
use App\Models\room;
use App\Models\task;
use App\Services\ParseToken\ParseToken;
use App\Supports\Responder;

class getRoomApproved
{
    private $parseToken;

    public function __construct(
        ParseToken $parseToken,
    ) {
        $this->parseToken = $parseToken;
    }

    public function getRoomApproved($request)
    {
        $employee = $this->parseToken->handle();
        $perPage = 10;
        $offset = ($request->page) * $perPage;

        $listRoomID = participant::where('employeeId', $employee->id)->where('status', '!=', ParticipantStatusEnum::Pending)
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
            $participantList = Employee::leftJoin('participants', 'participants.employeeId', '=', 'employee.id')
                ->select('employee.*', 'participants.participantId', 'participants.roomId')
                ->whereIn('employee.id', $participants)
                ->where('participants.roomId', $roomId)
                ->get();

            // Thêm danh sách người tham gia vào phòng
            $roomData['room'] = $room->toArray();
            $roomData['room']['participants'] = $participantList->toArray();

            // Thêm đếm nhiệm vụ 
            $taskCounts = task::leftJoin('task_participants', 'tasks.taskId', '=', 'task_participants.taskId')
                ->selectRaw(
                    '
                    COUNT(CASE WHEN task_participants.status = ? THEN 1 END) AS tasks_completed,
                    COUNT(CASE WHEN task_participants.status = ? THEN 1 END) AS tasks_pending,
                    COUNT(CASE WHEN task_participants.status = ? THEN 1 END) AS tasks_canceled',
                    [StatusTaskEnum::Done, StatusTaskEnum::Todo, StatusTaskEnum::Cancel]
                )
                ->where('tasks.roomId', $roomId)
                ->first();

            $roomData['room']['tasks_completed'] = $taskCounts->tasks_completed;
            $roomData['room']['tasks_pending'] = $taskCounts->tasks_pending;
            $roomData['room']['tasks_canceled'] = $taskCounts->tasks_canceled;

            $rooms[] = $roomData;
        }

        // Sắp xếp lại danh sách rooms theo updatedAt giảm dần
        usort($rooms, function ($a, $b) {
            return $b['room']['updatedAt'] <=> $a['room']['updatedAt'];
        });

        return Responder::success($rooms, 'Danh sách phòng');
    }
}
