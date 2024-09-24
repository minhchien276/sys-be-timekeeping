<?php

namespace App\ServicesAdmin\Overtime;

use App\Enums\TypeApplication;
use App\Models\overtime;
use Carbon\Carbon;

class indexOvertime
{
    public function index()
    {
        $overtime = overtime::leftJoin('application', 'overtime.applicationId', '=', 'application.id')
            ->leftJoin('employee', 'overtime.employeeId', '=', 'employee.id')
            ->select('employee.fullname', 'overtime.*', 'application.title', 'application.content', 'application.type', 'application.type', 'application.status', 'application.approverId', 'application.employeeId')
            ->where('application.type', TypeApplication::OverTime)
            ->orderBy('overtime.createdAt', 'desc')
            ->get();

        $search_date = '';

        $overtime->map(function ($item) {
            if ($item->dayOffDate) {
                $dayOffDate = Carbon::createFromTimestamp($item->dayOffDate / 1000);
                $item->dayOffDate = $dayOffDate->format('d-m-Y');
                $startTime = Carbon::createFromTimestamp($item->startTime / 1000);
                $item->startTime = $startTime->format('H:i:s');
                $endTime = Carbon::createFromTimestamp($item->endTime / 1000);
                $item->endTime = $endTime->format('H:i:s');
            }

            return $item;
        });

        return view('admin.overtime.index', compact('overtime', 'search_date'));
    }
}
