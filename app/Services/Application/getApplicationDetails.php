<?php

namespace App\Services\Application;

use App\Enums\TypeApplication;
use App\Models\application;
use App\Models\dayoff;
use App\Models\early_late;
use App\Models\employee;
use App\Models\notification;
use App\Models\overtime;
use App\Services\ParseToken\ParseToken;
use App\Supports\Responder;
use Exception;

class getApplicationDetails
{
    private $parseToken;

    public function __construct(
        ParseToken $parseToken
    ) {
        $this->parseToken = $parseToken;
    }

    public function handle($applicationId)
    {
        try {
            $employee = $this->parseToken->handle();
            $application = application::where('id', $applicationId)->first();

            notification::where('applicationId', '=', $applicationId)
                ->where('receiverId', '=', $employee->id)
                ->update(['seen' => 1]);

            if (!$application) {
                return Responder::fail(null, 'Không tìm thấy chi tiết đơn', 404);
            }

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

            return Responder::success($application, 'Chi tiết đơn từ');
        } catch (Exception $e) {
            return Responder::fail(null, $e->getMessage());
        }
    }
}
