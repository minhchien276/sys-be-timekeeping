<?php

namespace App\Services\Application;

use App\Enums\DepartmentEnum;
use App\Enums\RoleEnum;
use App\Models\employee;
use App\Supports\Responder;
use Exception;

class getReceiver
{
    public function handle($employeeId)
    {
        try {
            $employee = employee::where('id', $employeeId)->first();

            if (!$employee) {
                return Responder::fail(null, 'Không tìm thấy người nhận', 404);
            }

            if ($employee->roleId == RoleEnum::Leader) {
                $receiver = employee::leftJoin('department', 'department.id', '=', 'employee.departmentId')
                    ->leftJoin('roles', 'roles.id', '=', 'employee.roleId')
                    ->select('employee.*', 'department.name as departmentName', 'roles.name as roleName')
                    ->where(function ($query) use ($employee) {
                        $query->where('employee.roleId', RoleEnum::Director)
                            ->orWhere(function ($query) use ($employee) {
                                $query->where('employee.departmentId', DepartmentEnum::HR);
                            });
                    })
                    ->where('employee.status', 1)
                    ->orderBy('employee.keySearch', 'asc')
                    ->get();
            } else if ($employee->roleId != RoleEnum::Director && $employee->roleId != RoleEnum::Leader) {
                $receiver = employee::leftJoin('department', 'department.id', '=', 'employee.departmentId')
                    ->leftJoin('roles', 'roles.id', '=', 'employee.roleId')
                    ->where(function ($query) use ($employee) {
                        $query->where('employee.roleId', RoleEnum::Director)
                            ->orWhere(function ($query) use ($employee) {
                                $query->where('employee.departmentId', $employee->departmentId)
                                    ->where('employee.roleId', RoleEnum::Leader);
                            })
                            ->orWhere(function ($query) use ($employee) {
                                $query->where('employee.departmentId', DepartmentEnum::HR);
                            });
                    })
                    ->select('employee.*', 'department.name as departmentName', 'roles.name as roleName')
                    ->where('employee.status', 1)
                    ->orderBy('employee.keySearch', 'asc')
                    ->get();
            } else {
                $receiver = [];
            }

            return Responder::success($receiver, 'Danh sách người nhận');
        } catch (Exception $e) {
            return Responder::fail(null, $e->getMessage(), 404);
        }
    }
}
