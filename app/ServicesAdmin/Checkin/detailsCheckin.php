<?php

namespace App\ServicesAdmin\Checkin;

use App\Models\checkin;
use Carbon\Carbon;

class detailsCheckin
{
    public function indexDetails()
    {
        $email = '';
        $from_date = '';
        $to_date = '';
        $checkin = '';

        return view('admin.checkin.details', compact('checkin', 'from_date', 'to_date', 'email', 'checkin'));
    }

    public function CheckinDetails($request)
    {
        $email = $request->input('email');
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');

        $format_from_date = Carbon::createFromFormat('Y-m-d', $from_date);
        $format_to_date = Carbon::createFromFormat('Y-m-d', $to_date);

        $startTime = $format_from_date->startOfDay()->timestamp * 1000;
        $endTime = $format_to_date->endOfDay()->timestamp * 1000;

        $checkin = checkin::leftJoin('employee', 'checkin.employeeId', '=', 'employee.id')
            ->select('checkin.id', 'checkin.checkin', 'checkin.meter', 'checkin.location', 'employee.image', 'employee.fullname', 'employee.email', 'employee.departmentId', 'employee.id as employeeId')
            ->whereBetween('checkin.checkin', [$startTime, $endTime])
            ->where('employee.email', 'like', '%' . $email . '%')
            ->orderBy('checkin.checkin', 'desc')
            ->get();

        $checkin->map(function ($item) {
            if ($item->checkin) {
                $checkin = Carbon::createFromTimestamp($item->checkin / 1000);
                $item->checkin = $checkin->format('d-m-Y H:i:s');
            }

            return $item;
        });

        return view('admin.checkin.details', compact('checkin', 'from_date', 'to_date', 'email'));
    }
}
