<?php

namespace App\Services\Calendar;

use App\Enums\CalendarStatus;
use App\Enums\DepartmentEnum;
use App\Enums\RoleEnum;
use App\Enums\TypeApplication;
use App\Models\dayoff;
use App\Models\early_late;
use App\Models\employee;
use App\Services\ListDayOfDate\listHolidays;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class calendar
{
    private $listHolidays;

    public function __construct(
        listHolidays $listHolidays,
    ) {
        $this->listHolidays = $listHolidays;
    }

    function calculateStatus($data, $id)
    {
        $departmentId = employee::where('id', $id)->pluck('departmentId')->first();
        $roleId = employee::where('id', $id)->pluck('roleId')->first();
        $employee = employee::findOrFail($id);

        $lateTimeDepartment4 = '08:30:59';
        $lateTimeRole2 = '08:45:59';
        $lateTimeOthers = '09:00:59';
        $time_checkin_afternoon = '13:30:59';
        $morning_limit = '10:00:00';
        $afternoon_limit = '14:30:00';

        if ($departmentId == 4 && $roleId != 2) {
            $lateTime = $lateTimeDepartment4;
        } elseif ($roleId == 2) {
            $lateTime = $lateTimeRole2;
        } else {
            $lateTime = $lateTimeOthers;
        }

        $newRes = [];

        foreach ($data as $item) {
            $status = CalendarStatus::Origin;
            $today = Carbon::today()->timestamp * 1000;
            $late = null;
            $dayWork = 0;
            $dayOff = 0;
            $dayOffPaidLeave = 0;
            $date = Carbon::createFromTimestamp($item['date'] / 1000);
            if ($item['checkin'] != null) {
                if ($item['checkin']->checkin != null) {
                    $time_checkin = Carbon::createFromTimestampMs($item['checkin']->checkin)->format('H:i:s');
                    if ($today > $item['date']) {
                        if ($time_checkin > $lateTime) {
                            // đi muộn sáng
                            // if ($time_checkin < $morning_limit) {
                            //     $status = CalendarStatus::Lated;
                            //     $time1 = Carbon::createFromFormat('H:i:s', $lateTime);
                            //     $time2 = Carbon::createFromFormat('H:i:s', $time_checkin);
                            //     $late = ($time1->diff($time2))->format('%H:%I:%S');
                            //     $dayWork = 1;
                            // }

                            // if ($time_checkin > $morning_limit && $time_checkin < $time_checkin_afternoon) {
                            //     $status = CalendarStatus::Success;
                            //     $dayWork = 0.5;
                            // }
                            // // đi muộn chiều
                            // if ($time_checkin > $time_checkin_afternoon && $time_checkin < $afternoon_limit) {
                            //     $status = CalendarStatus::Lated;
                            //     $time1 = Carbon::createFromFormat('H:i:s', $time_checkin_afternoon);
                            //     $time2 = Carbon::createFromFormat('H:i:s', $time_checkin);
                            //     $late = ($time1->diff($time2))->format('%H:%I:%S');
                            //     $dayWork = 0.5;
                            // }

                            // if ($time_checkin > $afternoon_limit) {
                            //     $status = CalendarStatus::Missing;
                            //     $dayWork = 0;
                            // }
                            // if ($time_checkin > $afternoon && $time_checkin < $time_checkin_afternoon) {
                            //     $status = CalendarStatus::Success;
                            //     $dayWork = 0.5;
                            // } else {
                            //     $status = CalendarStatus::Lated;
                            //     $time1 = Carbon::createFromFormat('H:i:s', $lateTime);
                            //     $time2 = Carbon::createFromFormat('H:i:s', $time_checkin);
                            //     $late = ($time1->diff($time2))->format('%H:%I:%S');
                            //     $dayWork = 1;
                            // }


                            $status = CalendarStatus::Lated;
                            $time1 = Carbon::createFromFormat('H:i:s', $lateTime);
                            $time2 = Carbon::createFromFormat('H:i:s', $time_checkin);
                            $late = ($time1->diff($time2))->format('%H:%I:%S');
                            $dayWork = 1;

                        } elseif ($item['checkout'] === null) {
                            $status = CalendarStatus::Missing;
                            $dayWork = 0;
                        } elseif ($item['checkin'] === null) {
                            $status = CalendarStatus::Dayoff;
                            $dayWork = 1;
                        } else {
                            $status = CalendarStatus::Success;
                            $dayWork = 1;
                        }
                    } elseif ($today == $item['date']) {
                        $status = CalendarStatus::Pending;
                    } else {
                        $status = CalendarStatus::Origin;
                    }

                    if (Carbon::createFromTimestampMs($item['checkin']->checkin)->isSaturday()) {
                        $dayWork = 0.5;
                    }
                }
            }else {
                if ($today > $item['date']) {
                    if ($date->isSunday()) {
                        $status = CalendarStatus::Origin;
                        $dayWork = 0;
                    } else {
                        $status = CalendarStatus::Missing;
                        $dayWork = 0;
                    }
                }
            }

            $result = [
                'date' => $item['date'],
                'checkin' => $item['checkin'],
                'checkout' => $item['checkout'],
                'status' => $status,
                'daywork' => $dayWork,
                'dayoff' => $dayOff,
                'lated' => $late,
                'dayOffPaidLeave' => $dayOffPaidLeave,
            ];

            if (isset($this->listHolidays->handle()[$item['date']]) && $employee->createdAt < $item['date']) {
                $result = [
                    'date' => $item['date'],
                    'checkin' => $item['checkin'],
                    'checkout' => $item['checkout'],
                    'status' => CalendarStatus::Holiday,
                    'daywork' => $this->listHolidays->handle()[$item['date']],
                    'dayoff' => $dayOff,
                    'lated' => $late,
                    'dayOffPaidLeave' => $dayOffPaidLeave,
                ];
            }

            array_push($newRes, $result);
        }

        return $newRes;
    }

    public function handle($request, $listDate)
    {
        $checkin = DB::table('checkin')->where('employeeId', $request->id)->whereBetween('checkin', $listDate)->orderBy('checkin', 'asc')->get();
        $checkout = DB::table('checkout')->where('employeeId', $request->id)->whereBetween('checkout', $listDate)->orderBy('checkout', 'asc')->get();

        $roleId = employee::where('id', $request->id)->pluck('roleId')->first();
        $departmentId = employee::where('id', $request->id)->pluck('departmentId')->first();

        $in = 0;
        $out = 0;
        $res = [];

        for ($timestamp = $listDate[0]; $timestamp <= $listDate[1]; $timestamp += 86400000) {
            // 86400000 miliseconds = 1 ngày
            $date = $timestamp;
            $a = null;
            $b = null;
            $last = $date + 86400000 - 1000;
            if ($in < count($checkin)) {
                if ($date <= $checkin[$in]->checkin && $checkin[$in]->checkin <= $last) {
                    $a = $checkin[$in];
                    $in++;
                }
            }

            if ($out < count($checkout)) {
                if ($date <= $checkout[$out]->checkout && $checkout[$out]->checkout <= $last) {
                    $b = $checkout[$out];
                    $out++;
                }
            }

            array_push($res, [
                'date' => $date,
                'checkin' => $a,
                'checkout' => $b,
            ]);
        }

        if ($departmentId == DepartmentEnum::Driver) {
            $newRes = $this->calculateStatusDriver($res, $request->id);
        } elseif ($roleId == RoleEnum::PartTime) {
            $newRes = $this->calculateStatusParttime($res, $request->id);
        } else {
            $newRes = $this->calculateStatus($res, $request->id);
        }

        return $newRes;
    }

    public function calculateDayOff($listDate, $id, $current)
    {
        $today = Carbon::now()->startOfDay()->timestamp * 1000;
        $beginAfternoon = '13:30:59';

        $dayOff = dayoff::leftJoin('application', 'application.id', '=', 'dayoff.applicationId')
            ->select('dayoff.*', 'application.status')
            ->where('dayoff.employeeId', $id)
            ->whereBetween('dayoff.dayOffDate', $listDate)
            ->where('application.status', 1)
            ->orderBy('dayoff.dayOffDate', 'asc')
            ->get();

        $index = 0;

        foreach ($current as &$item) {
            if (sizeof($dayOff) > $index) {
                if ($item['date'] == $dayOff[$index]->dayOffDate && $item['date'] != $today) {
                    // Trưởng hợp nghỉ phép có lương
                    if ($dayOff[$index]->type == 0) {
                        // Trường hợp nghỉ buổi sáng
                        if ($dayOff[$index]->session == 1) {
                            // mặc định vào sáng thứ 7
                            $item['dayoff'] = 0.5;
                            $item['status'] = CalendarStatus::Dayoff;
                            $item['daywork'] = 0.5;
                            $item['dayOffPaidLeave'] = 0.5;
                            if ($item['checkin'] != null) {
                                $time_checkin = Carbon::createFromTimestampMs($item['checkin']->checkin)->format('H:i:s');
                                // Đến đúng giờ (không phải thứ 7)
                                if ($time_checkin < $beginAfternoon) {
                                    $item['status'] = CalendarStatus::Dayoff;
                                    $item['daywork'] = 1;
                                    $item['lated'] = null;
                                }
                                // Đến muộn (không phải thứ 7)
                                else {
                                    $item['status'] = CalendarStatus::Lated;
                                    $time1 = Carbon::createFromFormat('H:i:s', $beginAfternoon);
                                    $time2 = Carbon::createFromFormat('H:i:s', $time_checkin);
                                    $item['daywork'] = 1;
                                    $item['lated'] = ($time1->diff($time2))->format('%H:%I:%S');
                                }
                            }
                        } else if ($dayOff[$index]->session == 2) {
                            $item['dayoff'] = 0.5;
                            $item['dayOffPaidLeave'] = 0.5;
                            $item['daywork'] = 1;
                            $item['status'] = CalendarStatus::Dayoff;
                        } else {
                            $item['dayoff'] = 1;
                            $item['dayOffPaidLeave'] = 1;
                            $item['daywork'] = 1;
                            $item['status'] = CalendarStatus::Dayoff;
                        }
                    }
                    // Trường hợp nghỉ phép không lương
                    else if ($dayOff[$index]->type == 1) {
                        $item['status'] = CalendarStatus::Dayoff;
                        $item['daywork'] = 0;
                        $item['dayoff'] = 1;
                        // Trường hợp nghỉ buổi sáng
                        if ($dayOff[$index]->session == 1) {
                            $item['dayoff'] = 0.5;
                            $item['status'] = CalendarStatus::Dayoff;
                            $item['daywork'] = 0;
                            if ($item['checkin'] != null) {
                                $time_checkin = Carbon::createFromTimestampMs($item['checkin']->checkin)->format('H:i:s');
                                if ($time_checkin < $beginAfternoon) {
                                    $item['status'] = CalendarStatus::Dayoff;
                                    $item['daywork'] = 0.5;
                                    $item['lated'] = null;
                                } else {
                                    $item['status'] = CalendarStatus::Lated;
                                    $item['daywork'] = 0.5;
                                    $time1 = Carbon::createFromFormat('H:i:s', $beginAfternoon);
                                    $time2 = Carbon::createFromFormat('H:i:s', $time_checkin);
                                    $item['lated'] = ($time1->diff($time2))->format('%H:%I:%S');
                                }
                            }
                        } else if ($dayOff[$index]->session == 2) {
                            $item['dayoff'] = 0.5;
                            $item['status'] = CalendarStatus::Dayoff;
                            $item['daywork'] = 0.5;
                        }
                    } else {
                        $item['daywork'] = 0;
                        $item['dayoff'] = 1;
                    }
                    $index++;
                }
            }
        }

        return $current;
    }

    public function earlyLated($listDate, $id, $current)
    {
        $early_late = early_late::leftJoin('application', 'application.id', '=', 'early_late.applicationId')
            ->select('early_late.*', 'application.status')
            ->where('early_late.employeeId', $id)
            ->whereBetween('early_late.dayOffDate', $listDate)
            ->where('application.status', 1)
            ->orderBy('early_late.dayOffDate', 'asc')
            ->get();

        $index = 0;
        foreach ($current as &$item) {
            if (sizeof($early_late) > 0) {
                if (sizeof($early_late) > $index) {
                    if ($item['date'] == $early_late[$index]->dayOffDate) {
                        if (isset($early_late[$index])) {
                            if ($early_late[$index]->type == TypeApplication::Lated) {
                                if (isset($item['checkin'])) {
                                    if ($item['checkin']->checkin <= $early_late[$index]->hours) {
                                        $item['status'] = CalendarStatus::Success;
                                        $item['lated'] = null;
                                    }
                                }
                            } elseif ($early_late[$index]->type == TypeApplication::Early) {
                                if (isset($item['checkout'])) {
                                    if ($item['checkout']->checkout >= $early_late[$index]->hours) {
                                        $item['status'] = CalendarStatus::Success;
                                        $item['lated'] = null;
                                    }
                                }
                            }
                            $index++;
                        }
                    }
                }
            }
        }

        return $current;
    }

    function calculateStatusParttime($data, $id)
    {
        $afternoon = '12:00:00';
        $time_checkin_morning = '08:30:59';
        $time_checkin_afternoon = '13:30:59';
        $newRes = [];

        foreach ($data as $item) {
            $status = CalendarStatus::Origin;
            $today = Carbon::today()->timestamp * 1000;
            $late = null;
            $dayWork = 0;
            $dayOff = 0;
            $dayOffPaidLeave = 0;
            if ($item['checkin'] != null) {
                if ($item['checkin']->checkin != null) {
                    if ($today > $item['date']) {
                        if (!is_null($item['checkout']) && !is_null($item['checkin'])) {
                            $endDateTime = Carbon::createFromTimestampMs($item['checkin']->checkin);
                            $startDateTime = Carbon::createFromTimestampMs($item['checkout']->checkout);
                            $diff = $endDateTime->diff($startDateTime);

                            // Tính tổng số giờ chênh lệch
                            $hoursDiff = $diff->h + ($diff->days * 24) + ($diff->i / 60) + ($diff->s / 3600);

                            if ($hoursDiff < 5) {
                                $status = CalendarStatus::Success;
                                $dayWork = 1;
                            } else {
                                $status = CalendarStatus::Success;
                                $dayWork = 2;
                            }

                            // Tính thời gian đi muộn buổi sáng
                            $time_checkin = Carbon::createFromTimestampMs($item['checkin']->checkin)->format('H:i:s');
                            if ($time_checkin < $afternoon) {
                                if ($time_checkin > $time_checkin_morning) {
                                    $status = CalendarStatus::Lated;
                                    $time1 = Carbon::createFromFormat('H:i:s', $time_checkin_morning);
                                    $time2 = Carbon::createFromFormat('H:i:s', $time_checkin);
                                    $late = ($time1->diff($time2))->format('%H:%I:%S');
                                }
                            }

                            // Tính thời gian đi muộn buổi chiều
                            if ($time_checkin > $afternoon) {
                                if ($time_checkin > $time_checkin_afternoon) {
                                    $status = CalendarStatus::Lated;
                                    $time1 = Carbon::createFromFormat('H:i:s', $time_checkin_afternoon);
                                    $time2 = Carbon::createFromFormat('H:i:s', $time_checkin);
                                    $late = ($time1->diff($time2))->format('%H:%I:%S');
                                }
                            }
                        } elseif ($item['checkout'] === null) {
                            $status = CalendarStatus::Missing;
                            $dayWork = 0;
                        } elseif ($item['checkin'] === null) {
                            $status = CalendarStatus::Dayoff;
                            $dayWork = 1;
                        } else {
                            $status = CalendarStatus::Success;
                            $dayWork = 1;
                        }
                    } elseif ($today == $item['date']) {
                        $status = CalendarStatus::Pending;
                    } else {
                        $status = CalendarStatus::Origin;
                    }

                    if (Carbon::createFromTimestampMs($item['checkin']->checkin)->isSaturday()) {
                        $dayWork = 1;
                    }
                }
            }

            $result = [
                'date' => $item['date'],
                'checkin' => $item['checkin'],
                'checkout' => $item['checkout'],
                'status' => $status,
                'daywork' => $dayWork,
                'dayoff' => $dayOff,
                'dayOffPaidLeave' => $dayOffPaidLeave,
                'lated' => $late,
            ];

            if (isset($this->listHolidays->handle()[$item['date']])) {
                $result = [
                    'date' => $item['date'],
                    'checkin' => $item['checkin'],
                    'checkout' => $item['checkout'],
                    'status' => CalendarStatus::Holiday,
                    'daywork' => $this->listHolidays->handle()[$item['date']],
                    'dayoff' => $dayOff,
                    'dayOffPaidLeave' => $dayOffPaidLeave,
                    'lated' => $late,
                ];
            }

            array_push($newRes, $result);
        }

        return $newRes;
    }

    function calculateStatusDriver($data, $id)
    {
        $newRes = [];

        foreach ($data as $item) {
            $status = CalendarStatus::Origin;
            $today = Carbon::today()->timestamp * 1000;
            $late = null;
            $dayWork = 0;
            $dayOff = 0;
            $dayOffPaidLeave = 0;

            $date = Carbon::createFromTimestamp($item['date'] / 1000);

            if ($date->isSaturday()) {
                $status = CalendarStatus::Success;
                $dayWork = 0.5;
            } elseif ($date->isSunday()) {
                $status = CalendarStatus::Origin;
                $dayWork = 0;
            } else {
                $status = CalendarStatus::Success;
                $dayWork = 1;
            }

            $result = [
                'date' => $item['date'],
                'checkin' => $item['checkin'],
                'checkout' => $item['checkout'],
                'status' => $status,
                'daywork' => $dayWork,
                'dayoff' => $dayOff,
                'dayOffPaidLeave' => $dayOffPaidLeave,
                'lated' => $late,
            ];

            if (isset($this->listHolidays->handle()[$item['date']])) {
                $result = [
                    'date' => $item['date'],
                    'checkin' => $item['checkin'],
                    'checkout' => $item['checkout'],
                    'status' => CalendarStatus::Holiday,
                    'daywork' => $this->listHolidays->handle()[$item['date']],
                    'dayoff' => $dayOff,
                    'dayOffPaidLeave' => $dayOffPaidLeave,
                    'lated' => $late,
                ];
            }

            array_push($newRes, $result);
        }

        return $newRes;
    }
}
