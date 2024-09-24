<?php

namespace App\ServicesAdmin\Checkout;

use App\Models\checkout;
use Carbon\Carbon;

class detailsCheckout
{
    public function indexDetails()
    {
        $email = '';
        $from_date = '';
        $to_date = '';
        $checkout = '';

        return view('admin.checkout.details', compact('checkout', 'from_date', 'to_date', 'email', 'checkout'));
    }

    public function CheckoutDetails($request)
    {
        $email = $request->input('email');
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');

        $format_from_date = Carbon::createFromFormat('Y-m-d', $from_date);
        $format_to_date = Carbon::createFromFormat('Y-m-d', $to_date);

        $startTime = $format_from_date->startOfDay()->timestamp * 1000;
        $endTime = $format_to_date->endOfDay()->timestamp * 1000;

        $checkout = checkout::leftJoin('employee', 'checkout.employeeId', '=', 'employee.id')
            ->select('checkout.id', 'checkout.checkout', 'checkout.meter', 'checkout.location', 'employee.image', 'employee.fullname', 'employee.email', 'employee.departmentId', 'employee.id as employeeId')
            ->whereBetween('checkout.checkout', [$startTime, $endTime])
            ->where('employee.email', 'like', '%' . $email . '%')
            ->orderBy('checkout.checkout', 'desc')
            ->get();

        $checkout->map(function ($item) {
            if ($item->checkout) {
                $checkout = Carbon::createFromTimestamp($item->checkout / 1000);
                $item->checkout = $checkout->format('d-m-Y H:i:s');
            }

            return $item;
        });

        return view('admin.checkout.details', compact('checkout', 'from_date', 'to_date', 'email'));
    }
}
