<?php

namespace App\Services\Employee;

use App\Models\dayoff;
use App\Models\employee;
use App\Supports\Responder;
use Carbon\Carbon;
use Exception;

class getEmployeeDayoff
{
    public function handle()
    {
        try {
            $startOfDay = Carbon::now()->startOfDay()->timestamp * 1000;
            $endOfDay = Carbon::now()->endOfDay()->timestamp * 1000;

            $daysOff = dayoff::leftJoin('application', 'application.id', '=', 'dayoff.applicationId')
                ->where('dayoff.dayOffDate', '>=', $startOfDay)
                ->where('application.status', 1)
                ->orderBy('dayOffDate', 'asc')
                ->distinct()
                ->pluck('dayOffDate')
                ->take(3);

            $employees = employee::leftJoin('department', 'department.id', '=', 'employee.departmentId')
                ->leftJoin('roles', 'roles.id', '=', 'employee.roleId')
                ->leftJoin('dayoff', 'dayoff.employeeId', '=', 'employee.id')
                ->leftJoin('application', 'application.id', '=', 'dayoff.applicationId')
                ->select('employee.*', 'dayoff.dayOffDate', 'application.content', 'department.name as departmentName', 'roles.name as roleName', 'dayoff.dayOffDate as dayOff')
                ->where('application.status', 1)
                ->whereIn('dayoff.dayOffDate', $daysOff)
                ->where('employee.status', 1)
                ->orderBy('dayOffDate', 'asc')
                ->get();

            return Responder::success($employees, 'Danh sách nhân sự nghỉ phép hôm nay');
        } catch (Exception $e) {
            return Responder::fail(null, 'Không tìm thấy nhân sự nghỉ phép hôm nay', 404);
        }
    }
}
