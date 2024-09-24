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
use Illuminate\Support\Facades\DB;

class getById
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

            $applications = Application::leftJoin('employee', 'employee.id', '=', 'application.approverId')
                ->where('employeeId', $employee->id)
                ->select('application.*', 'employee.fullname as approverName')
                ->orderBy('createdAt', 'desc')
                ->skip($offset)
                ->take($perPage)
                ->get();

            $employee =  $employee->leftJoin('department', 'department.id', '=', 'employee.departmentId')
                ->leftJoin('roles', 'roles.id', '=', 'employee.roleId')
                ->where('employee.id', $employee->id)
                ->select('employee.*', 'department.name as departmentName', 'roles.name as roleName')
                ->first();

            foreach ($applications as $application) {
                if ($application->type == TypeApplication::PaidLeave || $application->type == TypeApplication::UnpaidLeave) {
                    $application->dayOff = dayoff::where('applicationId', $application->id)->orderBy('createdAt', 'desc')->get();

                    $application->employee = $employee;
                } elseif ($application->type == TypeApplication::OverTime) {
                    $application->overtime = overtime::where('applicationId', $application->id)->orderBy('createdAt', 'desc')->get();

                    $application->employee = $employee;
                } elseif ($application->type == TypeApplication::Early || $application->type == TypeApplication::Lated) {
                    $application->earlyLate = early_late::where('applicationId', $application->id)->orderBy('createdAt', 'desc')->get();

                    $application->employee = $employee;
                }
            }

            return Responder::success($applications, 'Danh sách đơn từ');
        } catch (Exception $e) {
            return Responder::fail(null, $e->getMessage(), 404);
        }
    }

    public function getApplications($request){
        try {
            $employee = $this->parseToken->handle();

            $perPage = 20;
            $offset = ($request->page) * $perPage;

            $applications = application::leftJoin('notification', 'notification.applicationId', '=', 'application.id')
                ->leftJoin('employee', 'employee.id', '=', 'application.approverId')
                ->select('application.*',
                        DB::raw('ANY_VALUE(notification.receiverId) as receiverId'),
                        DB::raw('ANY_VALUE(notification.senderId) as senderId'),
                        DB::raw('ANY_VALUE(notification.seen) as seen'),
                        DB::raw('ANY_VALUE(employee.fullname) as approverName'))
                ->where('notification.receiverId', $employee->id)
                ->orWhere('application.employeeId', $employee->id)
                ->distinct()
                ->orderBy('application.createdAt', 'desc')
                ->skip($offset)
                ->take($perPage)
                ->get();


            foreach ($applications as $application) {
                $senderId = $application->employeeId;
                $employee =  employee::leftJoin('department', 'department.id', '=', 'employee.departmentId')
                    ->leftJoin('roles', 'roles.id', '=', 'employee.roleId')
                    ->where('employee.id', $senderId)
                    ->select('employee.*', 'department.name as departmentName', 'roles.name as roleName')
                    ->first();

                if ($application->type == TypeApplication::PaidLeave || $application->type == TypeApplication::UnpaidLeave) {
                    $application->dayOff = dayoff::where('applicationId', $application->id)->orderBy('createdAt', 'desc')->get();

                    $application->employee = $employee;
                } elseif ($application->type == TypeApplication::OverTime) {
                    $application->overtime = overtime::where('applicationId', $application->id)->orderBy('createdAt', 'desc')->get();

                    $application->employee = $employee;
                } elseif ($application->type == TypeApplication::Early || $application->type == TypeApplication::Lated) {
                    $application->earlyLate = early_late::where('applicationId', $application->id)->orderBy('createdAt', 'desc')->get();

                    $application->employee = $employee;
                }
            }

            return Responder::success($applications, 'Danh sách đơn từ');
        } catch (Exception $e) {
            return Responder::fail(null, $e->getMessage(), 404);
        }
    }
}
