<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CalendarStatus;
use App\Enums\TypeApplication;
use App\Enums\TypeNotification;
use App\Exports\DayWorkExport;
use App\Http\Controllers\Controller;
use App\Imports\DayWorkImport;
use App\Models\employee;
use App\Models\notification;
use App\Models\overtime;
use App\Services\Calendar\calendar;
use App\Services\ListDayOfDate\countSaturdaysAndSundays;
use App\Services\ListDayOfDate\getListDayOfDate;
use App\Services\Notification\pushNotificationSpecific;
use App\Services\Notification\PushNotificationVer2;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SalaryController extends Controller
{
    private $calendar;
    private $getListDayOfDate;
    private $countSaturdaysAndSundays;
    private $pushNotificationSpecific;
    private $pushNotificationVer2;

    public function __construct(
        calendar $calendar,
        getListDayOfDate $getListDayOfDate,
        countSaturdaysAndSundays $countSaturdaysAndSundays,
        pushNotificationSpecific $pushNotificationSpecific,
        PushNotificationVer2 $pushNotificationVer2,
    ) {
        $this->calendar = $calendar;
        $this->getListDayOfDate = $getListDayOfDate;
        $this->countSaturdaysAndSundays = $countSaturdaysAndSundays;
        $this->pushNotificationSpecific = $pushNotificationSpecific;
        $this->pushNotificationVer2 = $pushNotificationVer2;
    }

    public function exportDaywork(Request $request)
    {
        try {
            // $listDate = $this->getListDayOfDate->handle();
            // $start = Carbon::createFromTimestampMs($listDate[0]);
            // $end = Carbon::createFromTimestampMs($listDate[1]);
            $startOfDay = Carbon::parse($request->startOfDay)->startOfDay();
            $endOfDay = Carbon::parse($request->endOfDay)->endOfDay();
            $listDate = [$startOfDay->timestamp * 1000, $endOfDay->timestamp * 1000];

            $totaldays = $this->countSaturdaysAndSundays->handle($startOfDay, $endOfDay);
            // Số ngày công thực tế trong tháng
            // $total_daywork = $totaldays['totaldays'] - $totaldays['sundays'] - ($totaldays['saturdays'] / 2);

            $employees = employee::get();
            $current = [];

            foreach ($employees as $item) {
                $currentItem = $this->calendar->handle($item, $listDate);
                $currentItem = $this->calendar->earlyLated($listDate, $item->id, $currentItem);
                $currentItem = $this->calendar->calculateDayOff($listDate, $item->id, $currentItem);
                $current[] = $currentItem;
            }

            $newCurrent = collect($current)->map(function ($record) use ($listDate) {
                $lated = 0;
                $missing = 0;
                $daywork = 0;
                $dayoff = 0;
                $dayOffPaidLeave = 0;
                $employeeId = null;
                $more5m = 0;
                foreach ($record as $item) {
                    // tính số buổi đi muộn
                    if ($item['status'] == CalendarStatus::Lated) {
                        $lated++;

                        if ($item['lated']) {
                            // số phút đi muộn
                            $laterTime = Carbon::createFromTimeString($item['lated']);
                            // mốc 5 phút
                            $fiveMinutes = Carbon::createFromTimeString("00:05:00");

                            if ($laterTime->greaterThan($fiveMinutes)) {
                                $more5m++;
                            }
                        }
                    }

                    // tính số buổi quên chấm công
                    if ($item['status'] == CalendarStatus::Missing && $item['dayoff'] == 0 && $item['dayOffPaidLeave'] == 0) {
                        $missing++;
                    }

                    // tính số công đi làm
                    $daywork += $item['daywork'];

                    // tính tổng số buổi nghỉ
                    $dayoff += $item['dayoff'];

                    // tính số buổi nghỉ có lương
                    $dayOffPaidLeave += $item['dayOffPaidLeave'];

                    if (isset($item['checkin'])) {
                        $employeeId = $item['checkin']->employeeId;
                    }
                }

                $overtime = overtime::leftJoin('application', 'application.id', '=', 'overtime.applicationId')
                    ->select('overtime.*', 'application.status', 'application.type')
                    ->where('overtime.employeeId', $employeeId)
                    ->where('application.type', TypeApplication::OverTime)
                    ->where('application.status', 1)
                    ->whereBetween('overtime.dayOffDate', $listDate)
                    ->get();

                $totalHoursOvertime = 0;
                foreach ($overtime as $item) {
                    $totalHoursOvertime += $item->hours;
                }
                // tính số công tăng ca
                $dayworkOvertime = ($totalHoursOvertime / 8) * 1.5;

                // tính số phép còn lại
                $dayOffLeft = employee::where('id', $employeeId)->pluck('dayOff')->first();

                // tính lương theo công trong tháng
                $salary_emp = employee::where('id', $employeeId)->pluck('salary')->first();
                $salary_real =  ($salary_emp / 23) * ($daywork + $dayworkOvertime);

                // tính tiền phạt quên chấm công và đi muộn
                // Trên 5p = 200k, dưới 5p = 100k, Quên cc = 50k/lần

                $punishPrice = 0;
                $punishPrice = ($more5m * 200000) + (($lated - $more5m) * 100000);
                // if ($lated >= 5) {
                //     $punishPrice = ($lated * 200000) + ($missing * 50000);
                // } else if ($lated == 4) {
                //     $punishPrice = 400000 + ($missing * 50000);
                // } else {
                //     $punishPrice = ($more5m * 100000) + ($missing * 50000);
                // }

                $record['lated'] = $lated;
                $record['missing'] = $missing;
                $record['daywork'] = $daywork;
                $record['dayOffPaidLeave'] = $dayOffPaidLeave;
                $record['dayoff'] = $dayoff;
                $record['dayOffLeft'] = $dayOffLeft;
                $record['employeeId'] = $employeeId;
                $record['dayworkOvertime'] = $dayworkOvertime;
                $record['salary'] = $salary_real;
                $record['more5m'] = $more5m;
                $record['less5m'] = $lated - $more5m;
                $record['punishPrice'] = $punishPrice;

                return $record;
            });

            $current = $newCurrent->toArray();

            $export = new DayWorkExport($current);

            return Excel::download($export, 'bang-luong.xlsx');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function indexImport()
    {
        $lastMonth = Carbon::now()->subMonth()->month;
        $currentYear = Carbon::now()->year;
        return view('admin.salary.index', compact('lastMonth','currentYear'));
    }

    public function importDaywork(Request $request)
    {
        try {
            $now = Carbon::now()->timestamp * 1000;

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $title = $request->input('title');
                $content = $request->input('content');

                Excel::import(new DayWorkImport, $file);

                $topic = "members-v2";

                $notification = notification::create([
                    'notiTitle' => $title,
                    'notiContent' => $content,
                    'receiverId' => 0,
                    'senderId' => $request->session()->get('user')->id,
                    'applicationId' => null,
                    'type' => TypeNotification::Salary,
                    'seen' => 1,
                    'createdAt' => $now,
                ]);

                $type_noti = "salary";
                $id_noti = $notification->id;

                $this->pushNotificationVer2->sendNotification($topic, $title, $content, $type_noti, $id_noti);

                return redirect()->back()->with('success', 'Import dữ liệu thành công!');
            } else {
                return redirect()->back()->with('error', 'Vui lòng chọn tệp Excel để import!');
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
