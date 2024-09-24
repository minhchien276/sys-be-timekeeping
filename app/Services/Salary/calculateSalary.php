<?php

namespace App\Services\Salary;

use App\Enums\RoleEnum;
use App\Enums\TypeApplication;
use App\Models\overtime;
use App\Services\Calendar\calendar;
use App\Services\ListDayOfDate\getListDayOfDate;
use App\Services\ParseToken\ParseToken;

class calculateSalary
{
    private $calendar;
    private $parseToken;
    private $getListDayOfDate;

    public function __construct(
        calendar $calendar,
        ParseToken $parseToken, 
        getListDayOfDate $getListDayOfDate
    ) {
        $this->calendar = $calendar;
        $this->parseToken = $parseToken;
        $this->getListDayOfDate = $getListDayOfDate;
    }

    public function handle($request)
    {
        $employee = $this->parseToken->handle();
        $listDate = $this->getListDayOfDate->handle();

        // công trong tháng
        $totalDaywork = $this->getDaywork($employee, $listDate);

        // thời gian tăng ca
        $totalHoursOvertime = $this->getOvertime($employee, $listDate);

        // lương cứng
        $salary = $employee->salary;

        dd($salary);
    }

    public function getDaywork($employee, $listDate)
    {
        $current = $this->calendar->handle($employee, $listDate);
        $current = $this->calendar->earlyLated($listDate, $employee->id, $current);
        $current = $this->calendar->calculateDayOff($listDate, $employee->id, $current);

        $totalDaywork = 0;
        foreach ($current as $daywork) {
            $totalDaywork += $daywork['daywork'];
        }

        if ($employee->roleId == RoleEnum::PartTime) {
            $totalDaywork = $totalDaywork * 2;
        }

        return $totalDaywork;
    }

    public function getOvertime($employee, $listDate)
    {
        $overtime = overtime::leftJoin('application', 'application.id', '=', 'overtime.applicationId')
            ->select('overtime.*', 'application.status', 'application.type')
            ->where('overtime.employeeId', $employee->id)
            ->where('application.type', TypeApplication::OverTime)
            ->where('application.status', 1)
            ->whereBetween('overtime.dayOffDate', $listDate)
            ->get();

        $totalHoursOvertime = 0;
        foreach ($overtime as $item) {
            $totalHoursOvertime += $item->hours;
        }

        return $totalHoursOvertime;
    }
}
