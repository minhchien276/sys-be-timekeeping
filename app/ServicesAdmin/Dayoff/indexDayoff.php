<?php

namespace App\ServicesAdmin\Dayoff;

use App\Enums\TypeApplication;
use App\Models\dayoff;
use Carbon\Carbon;

class indexDayoff
{
    public function index()
    {
        $dayoff = dayoff::leftJoin('application', 'dayoff.applicationId', '=', 'application.id')
            ->leftJoin('employee', 'dayoff.employeeId', '=', 'employee.id')
            ->select('employee.fullname', 'dayoff.*', 'application.title', 'application.content', 'application.type', 'application.type', 'application.status', 'application.approverId', 'application.employeeId')
            ->where('application.type', TypeApplication::UnpaidLeave)
            ->orWhere('application.type', TypeApplication::PaidLeave)
            ->orderBy('dayoff.createdAt', 'desc')
            ->get();

        $search_date = '';

        $dayoff->map(function ($item) {
            if ($item->dayOffDate) {
                $dayOffDate = Carbon::createFromTimestamp($item->dayOffDate / 1000);
                $item->dayOffDate = $dayOffDate->format('d-m-Y');

                $createdAt = Carbon::createFromTimestamp($item->createdAt / 1000);
                $item->createdAt = $createdAt->format('d-m-Y H:i:s');
            }

            return $item;
        });

        return view('admin.dayoff.index', compact('dayoff', 'search_date'));
    }
}
