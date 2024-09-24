<?php

namespace App\Services\WorkOrder\Task;

use App\Models\employee;
use App\Models\participant;
use App\Models\task;
use App\Services\ParseToken\ParseToken;
use App\Supports\Responder;
use Exception;
use Illuminate\Support\Facades\DB;

class searchTasks
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
            $perPage = 10;
            $offset = ($request->page) * $perPage;

            $searchTerm = '%' . $request->search . '%';

            $list_participant_id = participant::where('employeeId', $employee->id)->pluck('participantId')->toArray();

            $list_task_id = task::leftJoin('task_participants', 'task_participants.taskId', '=', 'tasks.taskId')
                ->whereIn('task_participants.participantId', $list_participant_id)
                ->where('task_participants.status', $request->status)
                ->pluck('task_participants.taskId')
                ->skip($offset)
                ->take($perPage)
                ->toArray();

            $list_tasks = DB::table('tasks')
                ->where(function ($query) use ($searchTerm) {
                    $query->where('title', 'like', $searchTerm)
                        ->orWhere('content', 'like', $searchTerm);
                })
                ->whereIn('taskId', $list_task_id)
                ->get();


            // lấy danh sách tasks
            $tasks = [];
            foreach ($list_tasks as $task) {
                $taskData = [];

                // Lấy danh sách những người tham gia của từng task
                $participantList = employee::leftJoin('participants', 'participants.employeeId', '=', 'employee.id')
                    ->leftJoin('task_participants', 'task_participants.participantId', '=', 'participants.participantId')
                    ->select('employee.*', 'participants.participantId', 'task_participants.taskId')
                    ->where('task_participants.taskId', $task->taskId)
                    ->get();

                // Thêm danh sách người tham gia vào task
                $taskData['task'] = (array) $task;
                $taskData['task']['participants'] = $participantList->toArray();

                $tasks[] = $taskData;
            }

            // Sắp xếp lại danh sách tasks theo updatedAt giảm dần
            usort($tasks, function ($a, $b) {
                return $b['task']['updatedAt'] <=> $a['task']['updatedAt'];
            });

            return Responder::success($tasks, 'Danh sách công việc');
        } catch (Exception $e) {
            return Responder::fail($e->getMessage(), 'Đã có lỗi sảy ra');
        }
    }
}
