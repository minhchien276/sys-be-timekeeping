<?php

namespace App\Services\Employee;

use App\Models\employee;
use App\Supports\Responder;
use Exception;

class getAllEmployee
{
    public function handle()
    {
        try {
            $employee = employee::leftJoin('department', 'department.id', '=', 'employee.departmentId')
                ->leftJoin('roles', 'roles.id', '=', 'employee.roleId')
                ->select('employee.*', 'department.name as departmentName', 'roles.name as roleName')
                ->where('employee.status', 1)
                ->get();

            return Responder::success($employee, 'Danh sách nhân sự');
        } catch (Exception $e) {
            return Responder::fail(null, 'Không tìm thấy nhân sự', 404);
        }
    }
}
