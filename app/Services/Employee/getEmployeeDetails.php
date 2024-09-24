<?php

namespace App\Services\Employee;

use App\Models\checkin;
use App\Models\checkout;
use App\Models\department;
use App\Models\employee;
use App\Services\ParseToken\ParseToken;
use App\Supports\Responder;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class getEmployeeDetails
{
    private $parseToken;

    public function __construct(
        ParseToken $parseToken
    ) {
        $this->parseToken = $parseToken;
    }
    public function handle($id)
    {
        try {
            $startOfDay = Carbon::now()->startOfDay()->timestamp * 1000;
            $endOfDay = Carbon::now()->endOfDay()->timestamp * 1000;

            $employee = employee::leftJoin('department', 'department.id', '=', 'employee.departmentId')
                ->leftJoin('roles', 'roles.id', '=', 'employee.roleId')
                ->where('employee.id', $id)
                ->select('employee.*', 'department.name as departmentName', 'roles.name as roleName')
                ->first();

            $checkin = checkin::where('checkin.employeeId', $id)
                ->whereBetween('checkin.checkin', [$startOfDay, $endOfDay])
                ->first();

            $checkout = checkout::where('checkout.employeeId', $id)
                ->whereBetween('checkout.checkout', [$startOfDay, $endOfDay])
                ->orderBy('checkout', 'desc')
                ->first();

            $timeKeeping = [
                'checkin' => $checkin ? $checkin : null,
                'checkout' => $checkout ? $checkout : null,
            ];

            $typeOfWork = [];

            if ($employee->departmentId == 4) {
                $typeOfWork['timeIn'] = "08:30:00";
                $typeOfWork['timeOut'] = "17:00:00";
            } else if ($employee->departmentId != 4 && $employee->roleId == 2) {
                $typeOfWork['timeIn'] = "08:45:00";
                $typeOfWork['timeOut'] = "17:30:00";
            } else {
                $typeOfWork['timeIn'] = "09:00:00";
                $typeOfWork['timeOut'] = "17:30:00";
            }

            $employee->hasLogout = false;

            $data = [
                'employee' => $employee,
                'timeKeeping' => $timeKeeping,
                'typeOfWork' => $typeOfWork,
            ];

            if ($data) {
                return Responder::success($data, 'Chi tiết thông tin nhân sự');
            }

            return Responder::success(null, 'Không tìm thấy thông tin nhân sự');
        } catch (Exception $e) {
            return Responder::fail(null, 'Không tìm thấy trang', 404);
        }
    }

    public function getLeader() {
        try {
            $employee = $this->parseToken->handle();
            $leader = employee::where('id', $employee->leaderId)->get();
            return Responder::success($leader, 'Danh sách leader');
        } catch (Exception $e) {
            return Responder::fail(null, 'Không tìm thấy leader', 404);
        }
    }
}
