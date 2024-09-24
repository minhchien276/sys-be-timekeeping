<?php

namespace App\Services\WorkOrder\Room;

use App\Enums\StatusTaskEnum;
use App\Models\employee;
use App\Models\participant;
use App\Models\task;
use App\Services\ParseToken\ParseToken;
use App\Supports\Responder;
use Illuminate\Support\Facades\DB;

class searchRoom
{
    private $parseToken;

    public function __construct(
        ParseToken $parseToken,
    ) {
        $this->parseToken = $parseToken;
    }

    public function handle($request)
    {
        try {
            $employee = $this->parseToken->handle();

            $searchTerm = '%' . $request->search . '%';

            $list_room_id = participant::where('employeeId', $employee->id)->pluck('roomId')->toArray();

            $list_rooms = DB::table('rooms')
                ->where('name', 'like', $searchTerm)
                ->whereIn('roomId', $list_room_id)
                ->get();

            if ($list_rooms->isEmpty()) {
                return Responder::fail(null, 'Không tìm thấy phòng', 404);
            }

            $rooms = [];
            foreach ($list_rooms as $room) {
                $roomData = [];

                // Lấy danh sách những người tham gia của từng phòng
                $participants = Participant::where('roomId', $room->roomId)->pluck('employeeId')->toArray();
                $participantList = Employee::leftJoin('participants', 'participants.employeeId', '=', 'employee.id')
                    ->select('employee.*', 'participants.participantId', 'participants.roomId')
                    ->whereIn('employee.id', $participants)
                    ->where('participants.roomId', $room->roomId)
                    ->get();

                // Thêm danh sách người tham gia vào phòng
                $roomData['room'] = (array) $room;
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
                    ->where('tasks.roomId', $room->roomId)
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
        } catch (\Exception $e) {
            return Responder::fail($e->getMessage(), 'Đã có lỗi xảy ra');
        }
    }
}
