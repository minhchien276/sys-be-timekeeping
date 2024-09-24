<?php

namespace App\ServicesAdmin\Employee;

use App\Enums\TypeNotification;
use App\Models\department;
use App\Models\employee;
use App\Models\notification;
use App\Models\roles;
use App\Services\Notification\pushNotificationSpecific;
use App\Services\Notification\PushNotificationVer2;
use Carbon\Carbon;
use Exception;

class createEmployee
{
    private $pushNotificationSpecific;
    private $pushNotificationVer2;

    public function __construct(
        pushNotificationSpecific $pushNotificationSpecific,
        PushNotificationVer2 $pushNotificationVer2,
    ) {
        $this->pushNotificationSpecific = $pushNotificationSpecific;
        $this->pushNotificationVer2 = $pushNotificationVer2;
    }

    public function create()
    {
        $department = department::select('id', 'name')->get();

        $role = roles::select('id', 'name')->get();
        $leaders = Employee::whereIn('roleId', [1, 2])->get();

        return view('admin.employee.create', compact('role', 'department', 'leaders'));
    }

    public function store($request)
    {
        try {
            $data = $request->only([
                'image', 'fullname', 'birthday', 'identification', 'salary',
                'dayOff', 'email', 'phone', 'departmentId', 'leaderId', 'roleId'
            ]);

            $employee = employee::createEmployee($data);

            $topic = "members-v2";

            $notification = notification::create([
                'notiTitle' => "WELLCOME TO TMSC GROUP",
                'notiContent' => "Chào mừng thành viên mới: " . $employee->fullname,
                'receiverId' => 0,
                'senderId' => $request->session()->get('user')->id,
                'applicationId' => $employee->id,
                'type' => TypeNotification::NewEmployee,
                'seen' => 1,
                'createdAt' => Carbon::now()->timestamp * 1000,
            ]);

            $type_noti = "new_employee";
            $id_noti = $employee->id;

            $this->pushNotificationVer2->sendNotification($topic, "WELLCOME TO TMSC GROUP", "Chào mừng thành viên mới: " . $employee->fullname, $type_noti, $id_noti);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Thêm nhân viên không thành công.');
        }

        return redirect()->back()->with('success', 'Thêm nhân viên thành công.');
    }
}
