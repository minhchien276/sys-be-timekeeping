<?php

namespace App\Services\Employee;

use App\Models\employee;
use App\Supports\Responder;
use Exception;

class getAll
{
    public function handle()
    {
        try {
            $count = employee::leftJoin('department', 'department.id', '=', 'employee.departmentId')
                ->leftJoin('roles', 'roles.id', '=', 'employee.roleId')
                ->select('employee.*', 'department.name as departmentName', 'roles.name as roleName')
                ->count();

            $data = [];

            for ($i = 0; $i < $count; $i++) {
                $employee = employee::leftJoin('department', 'department.id', '=', 'employee.departmentId')
                    ->leftJoin('roles', 'roles.id', '=', 'employee.roleId')
                    ->select('employee.*', 'department.name as departmentName', 'roles.name as roleName')
                    ->get();

                $data[] = $employee;
            }

            return Responder::success($data, 'Danh sách nhân sự');
        } catch (Exception $e) {
            return Responder::fail(null, 'Không tìm thấy nhân sự', 404);
        }
    }
}
