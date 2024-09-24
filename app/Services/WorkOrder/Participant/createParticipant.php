<?php

namespace App\Services\WorkOrder\Participant;

use App\Models\participant;
use App\Services\ParseToken\ParseToken;
use App\Supports\Responder;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class createParticipant
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
                'employeeId' => 'required|array',
                'roomId' => 'required|exists:rooms,roomId',
            ], [
                'name.required' => 'Tiêu đề là bắt buộc.',
                'employeeId.required' => 'Mã nhân viên là bắt buộc.',
                'employeeId.array' => 'Mã nhân viên phải là một mảng.',
                'roomId.required' => 'Phòng là bắt buộc.',
                'roomId.exists' => 'Phòng không tồn tại.',
            ]);

            if ($validator->fails()) {
                return Responder::fail(null, $validator->errors()->first(), 400);
            }

            $participants = [];

            DB::transaction(function () use ($request, $now, &$participants) {
                foreach ($request->employeeId as $item) {
                    $participant = participant::create([
                        "employeeId" => $item,
                        "name" => $request->name,
                        "roomId" => $request->roomId,
                        "createdAt" => $now,
                    ]);

                    if (!$participant) {
                        throw new Exception('Thêm nhân sự không thành công');
                    }

                    $participants[] = $participant;
                }
            });

            return Responder::success($participants, 'Thêm nhân sự thành công');
        } catch (Exception $e) {
            return Responder::fail(null, 'Thêm nhân sự không thành công');
        }
    }
}
