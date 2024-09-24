<?php

namespace App\Services\Exam;

use App\Models\employeetest;
use App\Supports\Responder;
use Carbon\Carbon;
use Exception;

class beginTest
{
    public function handle($employeeTestId)
    {
        try {
            $check = employeetest::where('employeeTestId', $employeeTestId)->first();
            if ($check->startTime === null) {
                $now = Carbon::now()->timestamp * 1000;
                employeetest::where('employeeTestId', $employeeTestId)->update([
                    'startTime' => $now,
                ]);
                return Responder::success(null, 'Bạn đã bắt đầu bài kiểm tra');
            }
            return Responder::fail(null, 'Bạn đã hoàn thành bài kiểm tra');
        } catch (Exception $e) {
            return Responder::fail(null, 'Bắt đầu bài kiểm tra không thành công');
        }
    }
}
