<?php

namespace App\Services\Document;

use App\Models\document;
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
                $document = document::join('employee', 'employee.id', '=', 'document.employeeId')
                    ->where('employee.departmentId', $departmentId)
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
