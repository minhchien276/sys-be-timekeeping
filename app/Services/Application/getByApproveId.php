<?php

namespace App\Services\Application;

use App\Enums\TypeApplication;
use App\Models\application;
use App\Models\dayoff;
use App\Models\early_late;
use App\Models\employee;
use App\Models\overtime;
use App\Services\ParseToken\ParseToken;
use App\Supports\Responder;
use Exception;

class getByApproveId
{
    private $parseToken;

    public function __construct(
        ParseToken $parseToken
    ) {
        $this->parseToken = $parseToken;
    }

    public function handle($request)
    {
        try {
            $employee = $this->parseToken->handle();
            $perPage = 20;
            $offset = ($request->page) * $perPage;

            $applications = application::leftJoin('notification', 'notification.applicationId', '=', 'application.id')
                ->select('application.*', 'notification.receiverId', 'notification.senderId', 'notification.seen')
                ->where('notification.receiverId', $employee->id)
                ->where('application.employeeId', '!=', $employee->id)
                ->orderBy('application.createdAt', 'desc')
                ->skip($offset)
                ->take($perPage)
                ->get();

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

            return response()->json([
                "data" => $applications,
                "message" => 'Danh sách đơn từ',
                "status" => true
            ]);
        } catch (Exception $e) {
            return Responder::fail(null, $e->getMessage(), 404);
        }
    }
}
