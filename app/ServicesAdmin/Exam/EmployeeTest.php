<?php

namespace App\ServicesAdmin\Exam;

use App\Enums\TypeNotification;
use App\Models\employee;
use App\Models\employeetest as modelEmployeetest;
use App\Models\notification;
use App\Models\test;
use App\Services\Notification\pushNotificationSpecific;
use App\Services\Notification\PushNotificationSpecificVer2;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EmployeeTest
{
    private $pushNotificationSpecific;
    private $pushNotificationSpecificVer2;

    public function __construct(
        pushNotificationSpecific $pushNotificationSpecific,
        PushNotificationSpecificVer2 $pushNotificationSpecificVer2
    ) {
        $this->pushNotificationSpecific = $pushNotificationSpecific;
        $this->pushNotificationSpecificVer2 = $pushNotificationSpecificVer2;
    }

    public function createEmployeeTest()
    {
        $tests = test::get();

        $employees = employee::where('status', 1)->get();

        return view('admin.exam.createEmployeeTest', compact('tests', 'employees'));
    }

    public function storeEmployeeTest($request)
    {
        try {
            DB::beginTransaction();
            $now = Carbon::now()->timestamp * 1000;
            $expired = Carbon::parse($request->expired)->timestamp * 1000;

            $selectedEmployees = $request->input('employee');
            $title = test::where('testId', $request->testId)->pluck('title')->first();

            $employeeTests = [];

            foreach ($selectedEmployees as $employeeId) {
                $employeeTest = modelEmployeetest::create([
                    'employeeId' => $employeeId,
                    'testId' => $request->testId,
                    'expired' => $expired,
                    'createdAt' => $now,
                ]);

                notification::create([
                    "receiverId" => $employeeId,
                    "senderId" => $request->session()->get('user')->id,
                    "applicationId" => $employeeTest->employeeTestId,
                    "type" => TypeNotification::Exam,
                    'seen' => 0,
                    'createdAt' => $now,
                ]);

                $employeeTests[] = $employeeTest->employeeTestId;
            }

            DB::commit();
            $success = true;
        } catch (\Exception $e) {
            DB::rollback();
            $success = false;
            $errorMessage = $e->getMessage();
        } finally {
            if ($success) {
                $deviceTokens = employee::whereIn('id', $selectedEmployees)
                    ->pluck('device_token')
                    ->toArray();

                $type_noti = "test_details";
                $id_noti = !empty($employeeTests) ? $employeeTests[0] : null;

                if ($id_noti) {
                    $this->pushNotificationSpecificVer2->sendNotification($deviceTokens, 'KIỂM TRA ĐỊNH KỲ', $title, $type_noti, $id_noti);
                }

                return redirect()->back()->with('success', 'Bài kiểm tra đã được tạo thành công.');
            } else {
                return redirect()->back()->with('error', $errorMessage);
            }
        }
    }
}
