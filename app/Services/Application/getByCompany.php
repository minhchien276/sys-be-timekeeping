<?php

namespace App\Services\Application;

use App\Models\application;
use App\Models\employee;
use App\Supports\Responder;
use Exception;

class getByCompany
{
    public function handle($employeeId)
    {
        try {
            $employee = employee::where('id', $employeeId)->first();

            $email = $employee->email;
            $emailCheck = substr($email, strpos($email, '@') + 1);

            if ($employee->roleId == 1) {
                $application = application::join('employee', 'employee.id', '=', 'application.employeeId')
                    ->where('employee.email', 'LIKE', '%' . $emailCheck)
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
