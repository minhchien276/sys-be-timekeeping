<?php

namespace App\Services\WorkOrder\Participant;

use App\Enums\ParticipantStatusEnum;
use App\Models\participant;
use App\Supports\Responder;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class updateStatusParticipant
{

    public function handle($request, $participantId)
    {
        try {
            $participant = Participant::findOrFail($participantId);
            $status = $request->status;

            $validStatuses = [
                ParticipantStatusEnum::Approved => 'Tham gia phòng thành công',
                ParticipantStatusEnum::Cancel => 'Hủy lời mời tham gia phòng thành công',
                ParticipantStatusEnum::Leave => 'Rời phòng thành công',
            ];

            if (!array_key_exists($status, $validStatuses)) {
                return Responder::fail(null, 'Trạng thái không hợp lệ');
            }

            $participant->update([
                'status' => $status,
                'updatedAt' => Carbon::now()->getTimestampMs(),
            ]);

            return Responder::success($participant, $validStatuses[$status]);
        } catch (ModelNotFoundException $e) {
            return Responder::fail(null, 'Không tìm thấy người tham gia');
        } catch (Exception $e) {
            return Responder::fail(null, 'Tham gia phòng thất bại');
        }
    }
}
