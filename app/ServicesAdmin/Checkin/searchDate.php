<?php

namespace App\ServicesAdmin\Checkin;

use App\Models\checkin;
use Carbon\Carbon;

class searchDate
{
    public function handle($request)
    {
        $search_date = $request->input('search_date');

        $format_search_date = Carbon::createFromFormat('Y-m-d', $search_date);

        $startTime = $format_search_date->startOfDay()->timestamp * 1000;

        $endTime = $format_search_date->endOfDay()->timestamp * 1000;

        $checkin = checkin::leftJoin('employee', 'checkin.employeeId', '=', 'employee.id')
            ->select('checkin.id', 'checkin.checkin', 'checkin.meter', 'checkin.location', 'employee.image', 'employee.fullname', 'employee.email', 'employee.id as employeeId')
            ->whereBetween('checkin.checkin', [$startTime, $endTime])
            ->orderBy('checkin.checkin', 'desc')
            ->get();

        $checkin->map(function ($item) {
            if ($item->checkin) {
                $checkin = Carbon::createFromTimestamp($item->checkin / 1000);
                $item->checkin = $checkin->format('d-m-Y H:i:s');
            }

            return $item;
        });

        return view('admin.checkin.index', compact('checkin', 'search_date'));
    }
}
