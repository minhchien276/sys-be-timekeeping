<?php

namespace App\ServicesAdmin\EarlyLated;

use App\Enums\TypeApplication;
use App\Models\early_late;
use Carbon\Carbon;

class searchDate
{
    public function handle($request)
    {
        $search_date = $request->input('search_date');

        $format_search_date = Carbon::createFromFormat('Y-m-d', $search_date);

        $startTime = $format_search_date->startOfDay()->timestamp * 1000;

        $endTime = $format_search_date->endOfDay()->timestamp * 1000;

        $early_late = early_late::leftJoin('application', 'early_late.applicationId', '=', 'application.id')
            ->leftJoin('employee', 'early_late.employeeId', '=', 'employee.id')
            ->select('employee.fullname', 'early_late.*', 'application.title', 'application.content', 'application.type', 'application.type', 'application.status', 'application.approverId', 'application.employeeId')
            ->whereBetween('early_late.createdAt', [$startTime, $endTime])
            ->where(function ($query) {
                $query->where('application.type', TypeApplication::Early)
                    ->orWhere('application.type', TypeApplication::Lated);
            })
            ->orderBy('early_late.createdAt', 'desc')
            ->get();

        $early_late->map(function ($item) {
            if ($item->dayOffDate) {
                $dayOffDate = Carbon::createFromTimestamp($item->dayOffDate / 1000);
                $item->dayOffDate = $dayOffDate->format('d-m-Y');

                $hours = Carbon::createFromTimestamp($item->hours / 1000);
                $item->hours = $hours->format('H:i:s');
            }

            return $item;
        });

        return view('admin.early_late.index', compact('early_late', 'search_date'));
    }
}
