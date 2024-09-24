<?php

namespace App\Services\Employee;

use App\Models\employee;
use App\Supports\Responder;
use Carbon\Carbon;
use Exception;

class getBirthdayToday
{
    public function handle()
    {
        try {
            $today = Carbon::now()->format('m-d');

            $employee = employee::leftJoin('department', 'department.id', '=', 'employee.departmentId')
                ->leftJoin('roles', 'roles.id', '=', 'employee.roleId')
                ->select('employee.*', 'department.name as departmentName', 'roles.name as roleName')
                ->where('employee.status', 1)
                ->whereRaw("DATE_FORMAT(employee.birthday, '%m-%d') = ?", [$today])
                ->get();

            return Responder::success($employee, 'Danh sách nhân sự sinh nhật hôm nay');
        } catch (Exception $e) {
            return Responder::fail(null, 'Không tìm thấy nhân sự nào sinh nhật hôm nay', 404);
        }
    }
}
