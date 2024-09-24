<?php

namespace App\Services\WorkOrder\TaskParticipant;

use App\Enums\RoleEnum;
use App\Enums\StatusTaskEnum;
use App\Models\task_participant;
use App\Services\ParseToken\ParseToken;
use App\Supports\Responder;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class createTaskParticipant
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
                'name' => 'required',
                'expired' => 'required',
                'participantId' => 'required|array',
                'taskId' => 'required|exists:tasks,taskId',
            ], [
                'name.required' => 'Tiêu đề là bắt buộc.',
                'expired.required' => 'Thời hạn là bắt buộc.',
                'participantId.required' => 'Mã nhân viên là bắt buộc.',
                'participantId.array' => 'Mã nhân viên phải là một mảng.',
                'taskId.required' => 'Nhiệm vụ là bắt buộc.',
                'taskId.exists' => 'Nhiệm vụ không tồn tại.',
            ]);

            if ($validator->fails()) {
                return Responder::fail(null, $validator->errors()->first(), 400);
            }

            $task_participants = [];

            DB::transaction(function () use ($request, $now, &$task_participants) {
                foreach ($request->participantId as $item) {
                    $task_participant = task_participant::create([
                        "participantId" => $item,
                        "expired" => $request->expired,
                        "taskId" => $request->taskId,
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

            return Responder::success($task_participants, 'Giao nhiệm vụ thành công');
        } catch (Exception $e) {
            return Responder::fail($e, 'Giao nhiệm vụ không thành công');
        }
    }
}
