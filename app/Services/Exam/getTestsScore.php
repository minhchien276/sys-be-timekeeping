<?php

namespace App\Services\Exam;

use App\Enums\DepartmentEnum;
use App\Models\employeetest;
use App\Supports\Responder;
use Exception;

class getTestsScore
{
    public function handle($testId)
    {
        try {
            $tests = employeetest::join('employee', 'employeeId', '=', 'id')
                ->select('employeetests.*', 'employee.fullname', 'employee.departmentId')
                ->where('employee.departmentId', '!=', DepartmentEnum::Director)
                ->where('testId', $testId)
                ->get();

            return Responder::success($tests, 'Lấy danh sách điểm thành công');
        } catch (Exception $e) {
            return Responder::fail(null, 'Lấy danh sách điểm không thành công');
        }
    }
}
