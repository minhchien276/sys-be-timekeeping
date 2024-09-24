<?php

namespace App\Services\Employee;

use App\Enums\RoleEnum;
use App\Enums\TypeApplication;
use App\Models\application;
use App\Models\dayoff;
use App\Models\early_late;
use App\Models\employee;
use App\Models\overtime;
use App\Services\ParseToken\ParseToken;
use App\Supports\Responder;
use Carbon\Carbon;
use Exception;

class getHomeAdmin
{
    private $parseToken;

    public function __construct(
        ParseToken $parseToken
    ) {
        $this->parseToken = $parseToken;
    }

    public function handle()
    {
        try {
            $roleId = $this->parseToken->handle()->roleId;

            if ($roleId != RoleEnum::Director) {
                return Responder::fail(null, 'Bạn không có quyền truy cập', 403);
            }

            $startOfDay = Carbon::now()->startOfDay()->timestamp * 1000;
            $endOfDay = Carbon::now()->endOfDay()->timestamp * 1000;

            $dayoff = dayoff::whereBetween('dayoff.dayOffDate', [$startOfDay, $endOfDay])->pluck('employeeId')->toArray();

            $employeeOnline = employee::leftJoin('department', 'department.id', '=', 'employee.departmentId')
                ->leftJoin('roles', 'roles.id', '=', 'employee.roleId')
                ->select('employee.*', 'department.name as departmentName', 'roles.name as roleName')
                ->whereNotIn('employee.id', $dayoff)
                ->where('employee.status', 1)
                ->get();

            $employeeDayoff = employee::leftJoin('department', 'department.id', '=', 'employee.departmentId')
                ->leftJoin('roles', 'roles.id', '=', 'employee.roleId')
                ->leftJoin('dayoff', 'dayoff.employeeId', '=', 'employee.id')
                ->select('employee.*', 'dayoff.dayOffDate', 'department.name as departmentName', 'roles.name as roleName')
                ->whereBetween('dayoff.dayOffDate', [$startOfDay, $endOfDay])
                ->where('employee.status', 1)
                ->get();

            $applications = application::whereNull('status')->get();

            foreach ($applications as $application) {
                if ($application->type == TypeApplication::PaidLeave || $application->type == TypeApplication::UnpaidLeave) {
                    $application->dayOff = dayoff::where('applicationId', $application->id)->orderBy('createdAt', 'desc')->get();

                    $application->employee = employee::leftJoin('department', 'department.id', '=', 'employee.departmentId')
                        ->leftJoin('roles', 'roles.id', '=', 'employee.roleId')
                        ->where('employee.id', $application->employeeId)
                        ->select('employee.*', 'department.name as departmentName', 'roles.name as roleName')
                        ->first();
                } elseif ($application->type == TypeApplication::OverTime) {
                    $application->overtime = overtime::where('applicationId', $application->id)->orderBy('createdAt', 'desc')->get();

                    $application->employee = employee::leftJoin('department', 'department.id', '=', 'employee.departmentId')
                        ->leftJoin('roles', 'roles.id', '=', 'employee.roleId')
                        ->where('employee.id', $application->employeeId)
                        ->select('employee.*', 'department.name as departmentName', 'roles.name as roleName')
                        ->first();
                } elseif ($application->type == TypeApplication::Early || $application->type == TypeApplication::Lated) {
                    $application->earlyLate = early_late::where('applicationId', $application->id)->orderBy('createdAt', 'desc')->get();

                    $application->employee = employee::leftJoin('department', 'department.id', '=', 'employee.departmentId')
                        ->leftJoin('roles', 'roles.id', '=', 'employee.roleId')
                        ->where('employee.id', $application->employeeId)
                        ->select('employee.*', 'department.name as departmentName', 'roles.name as roleName')
                        ->first();
                }
            }

            $meeting = null;

            $data = [
                'employeeOnline' => $employeeOnline,
                'employeeDayoff' => $employeeDayoff,
                'applications' => $applications,
                'meeting' => $meeting,
            ];

            if ($data) {
                return Responder::success($data, 'Chi tiết thông tin');
            }

            return Responder::success(null, 'Không tìm thấy thông tin');
        } catch (Exception $e) {
            return Responder::fail(null, 'Không tìm thấy trang', 404);
        }
    }
}
