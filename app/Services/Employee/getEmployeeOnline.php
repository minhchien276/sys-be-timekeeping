<?php

namespace App\Services\Employee;

use App\Models\checkin;
use App\Models\dayoff;
use App\Models\employee;
use App\Supports\Responder;
use Carbon\Carbon;
use Exception;

class getEmployeeOnline
{
    public function handle()
    {
        try {
            $startOfDay = Carbon::now()->startOfDay()->timestamp * 1000;
            $endOfDay = Carbon::now()->endOfDay()->timestamp * 1000;

            // $dayoff = dayoff::whereBetween('dayoff.dayOffDate', [$startOfDay, $endOfDay])->pluck('employeeId')->toArray();

            $checkin = checkin::whereBetween('checkin.checkin', [$startOfDay, $endOfDay])->pluck('employeeId')->toArray();

            // $employees = employee::leftJoin('department', 'department.id', '=', 'employee.departmentId')
            //     ->leftJoin('roles', 'roles.id', '=', 'employee.roleId')
            //     ->select('employee.*', 'department.name as departmentName', 'roles.name as roleName')
            //     ->whereNotIn('employee.id', $dayoff)
            //     ->where('employee.status', 1)
            //     ->get();

            $employees = employee::whereIn('id', $checkin)->get();

            return Responder::success($employees, 'Danh sách nhân sự đi làm hôm nay');
        } catch (Exception $e) {
            return Responder::fail(null, 'Không tìm thấy nhân sự đi làm hôm nay', 404);
        }
    }
}
