<?php

namespace App\ServicesAdmin\Checkout;

use App\Models\checkout;
use Carbon\Carbon;

class searchDate
{
    public function handle($request)
    {
        $search_date = $request->input('search_date');

        $format_search_date = Carbon::createFromFormat('Y-m-d', $search_date);

        $startTime = $format_search_date->startOfDay()->timestamp * 1000;

        $endTime = $format_search_date->endOfDay()->timestamp * 1000;

        $checkout = checkout::leftJoin('employee', 'checkout.employeeId', '=', 'employee.id')
            ->select('checkout.id', 'checkout.checkout', 'checkout.meter', 'checkout.location', 'employee.image', 'employee.fullname', 'employee.email', 'employee.id as employeeId')
            ->whereBetween('checkout.checkout', [$startTime, $endTime])
            ->orderBy('checkout.checkout', 'desc')
            ->get();

        $checkout->map(function ($item) {
            if ($item->checkout) {
                $checkout = Carbon::createFromTimestamp($item->checkout / 1000);
                $item->checkout = $checkout->format('d-m-Y H:i:s');
            }

            return $item;
        });

        return view('admin.checkout.index', compact('checkout', 'search_date'));
    }
}
