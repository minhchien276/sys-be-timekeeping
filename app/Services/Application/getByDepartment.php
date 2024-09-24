<?php

namespace App\Services\Application;

use App\Models\application;
use App\Models\employee;
use App\Supports\Responder;
use Exception;

class getByDepartment
{
    public function handle($employeeId, $departmentId)
    {
        try {
            $employee = employee::where('id', $employeeId)->pluck('roleId')->first();

            if ($employee == 1 || $employee == 2) {
                $application = application::join('employee', 'employee.id', '=', 'application.employeeId')
                    ->where('employee.departmentId', $departmentId)
                    ->get();

                return Responder::success($application, 'Danh sách đơn từ');
            } else {
                return Responder::fail(null, 'Bạn không có quyền truy cập', 403);
            }
        } catch (Exception $e) {
            return Responder::fail(null, 'Không tìm thấy đơn từ', 404);
        }
    }
}
