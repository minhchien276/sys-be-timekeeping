<?php

namespace App\ServicesAdmin\Dayoff;

use App\Enums\TypeApplication;
use App\Models\dayoff;
use Carbon\Carbon;

class searchDate
{
    public function handle($request)
    {
        $search_date = $request->input('search_date');

        $format_search_date = Carbon::createFromFormat('Y-m-d', $search_date);

        $startTime = $format_search_date->startOfDay()->timestamp * 1000;

        $endTime = $format_search_date->endOfDay()->timestamp * 1000;

        $dayoff = dayoff::leftJoin('application', 'dayoff.applicationId', '=', 'application.id')
            ->leftJoin('employee', 'dayoff.employeeId', '=', 'employee.id')
            ->select('employee.fullname', 'dayoff.*', 'application.title', 'application.content', 'application.type', 'application.type', 'application.status', 'application.approverId', 'application.employeeId')
            ->whereBetween('dayoff.createdAt', [$startTime, $endTime])
            ->where(function ($query) {
                $query->where('application.type', TypeApplication::UnpaidLeave)
                    ->orWhere('application.type', TypeApplication::PaidLeave);
            })
            ->orderBy('dayoff.createdAt', 'desc')
            ->get();

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
