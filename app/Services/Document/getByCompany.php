<?php

namespace App\Services\Document;

use App\Models\document;
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
                $document = document::join('employee', 'employee.id', '=', 'document.employeeId')
                    ->where('employee.email', 'LIKE', '%' . $emailCheck)
                    ->get();

                return Responder::success($document, 'Danh sách hồ sơ');
            } else {
                return Responder::fail(null, 'Bạn không có quyền truy cập', 403);
            }
        } catch (Exception $e) {
            return Responder::fail(null, 'Không tìm thấy hồ sơ', 404);
        }
    }
}
