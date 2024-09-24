<?php

namespace App\Services\Employee;

use App\Models\employee;
use App\Supports\Responder;
use Carbon\Carbon;
use Exception;

class getOnBoardToday
{
    public function handle()
    {
        try {
            $start = Carbon::today()->startOfDay()->timestamp * 1000;
            $end = Carbon::today()->endOfDay()->timestamp * 1000;

            $employee = employee::leftJoin('department', 'department.id', '=', 'employee.departmentId')
                ->leftJoin('roles', 'roles.id', '=', 'employee.roleId')
                ->select('employee.*', 'department.name as departmentName', 'roles.name as roleName')
                ->where('employee.status', 1)
                ->whereBetween('employee.createdAt', [$start, $end])
                ->get();

            return Responder::success($employee, 'Danh sách nhân sự mới hôm nay');
        } catch (Exception $e) {
            return Responder::fail(null, 'Không tìm thấy nhân sự nào mới hôm nay', 404);
        }
    }
}
