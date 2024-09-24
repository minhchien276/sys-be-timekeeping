<?php

namespace App\Services\WorkOrder\Task;

use App\Enums\RoleEnum;
use App\Enums\StatusTaskEnum;
use App\Models\task;
use App\Models\task_participant;
use App\Services\ParseToken\ParseToken;
use App\Supports\Responder;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class createTask
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
            $now = Carbon::now()->timestamp * 1000;

            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'content' => 'required',
                'expired' => 'required',
                'roomId' => 'required|exists:rooms,roomId',
            ], [
                'title.required' => 'Tiêu đề là bắt buộc.',
                'content.required' => 'Nội dung là bắt buộc.',
                'expired.required' => 'Thời hạn là bắt buộc.',
                'roomId.required' => 'Phòng là bắt buộc.',
                'roomId.exists' => 'Phòng không tồn tại.',
            ]);

            if ($validator->fails()) {
                return Responder::fail(null, $validator->errors()->first(), 400);
            }

            $task = task::create([
                "employeeId" => $employee->id,
                "title" => $request->title,
                "content" => $request->content,
                "expired" => $request->expired,
                "roomId" => $request->roomId,
                "createdAt" => $now,
            ]);

            if (!$task) {
                return Responder::fail(null, 'Tạo công việc không thành công');
            }

            $task_participants = [];

            DB::transaction(function () use ($request, $now, &$task_participants, $task) {
                foreach ($request->participantId as $item) {
                    $task_participant = task_participant::create([
                        "participantId" => $item,
                        "expired" => $request->expired,
                        "taskId" => $task->taskId,
                        "status" => StatusTaskEnum::Todo,
                        "note" => $request->note,
                        "createdAt" => $now,
                    ]);

                    if (!$task_participant) {
                        throw new Exception('Giao nhiệm vụ không thành công!');
                    }

                    $task_participants[] = $task_participant;
                }
            });

            return Responder::success($task, 'Tạo công việc thành công');
        } catch (Exception $e) {
            return Responder::fail(null, 'Tạo công việc không thành công');
        }
    }
}
