<?php

namespace App\Services\Document;

use App\Models\document;
use App\Supports\Responder;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Validator;

class createDocument
{
    public function handle($request)
    {
        $now = Carbon::now()->timestamp * 1000;

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'employeeId' => 'required',
        ], [
            'name.required' => 'Tiêu đề là bắt buộc.',
            'employeeId.required' => 'Mã nhân viên là bắt buộc.',
        ]);

        if ($validator->fails()) {
            return Responder::fail(null, $validator->errors()->first(), 400);
        }

        try {
            $document = document::create([
                'name' => $request->name,
                'employeeId' => $request->employeeId,
                'createdAt' => $now,
            ]);

            return Responder::success($document, 'Thêm mới hồ sơ thành công');
        } catch (Exception $e) {
            return Responder::fail(null, 'Thêm mới hồ sơ thất bại', 400);
        }
    }
}
