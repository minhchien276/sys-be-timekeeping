<?php

namespace App\Services\WorkOrder\Task;

use App\Models\task;
use App\Services\ParseToken\ParseToken;
use App\Supports\Responder;
use Exception;

class getTaskByParticipant
{
    private $parseToken;

    public function __construct(
        ParseToken $parseToken
    ) {
        $this->parseToken = $parseToken;
    }

    public function handle($request)
    {
        try {
            $employee = $this->parseToken->handle();

            $task = task::leftJoin('task_participants', 'tasks.taskId', '=', 'task_participants.taskId')
                ->leftJoin('participants', 'participants.participantId', '=', 'task_participants.participantId')
                ->select('participants.employeeId', 'tasks.taskId', 'tasks.title', 'tasks.content', 'tasks.roomId', 'tasks.employeeId', 'task_participants.*')
                ->where(function ($query) use ($employee) {
                    $query->where('participants.employeeId', $employee->id)
                        ->orWhere('tasks.employeeId', $employee->id);
                })
                ->where('task_participants.status', $request->status)
                ->get();

            if (!$task) {
                return Responder::fail(null, 'Không tìm thấy danh sách công việc');
            }

            return Responder::success($task, 'Danh sách công việc');
        } catch (Exception $e) {
            return Responder::fail($e->getMessage(), 'Đã có lỗi sảy ra');
        }
    }
}
