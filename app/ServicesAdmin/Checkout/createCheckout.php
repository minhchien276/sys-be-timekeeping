<?php

namespace App\ServicesAdmin\Checkout;

use App\Models\checkout;
use App\Models\employee;
use Carbon\Carbon;
use Exception;

class createCheckout
{
    public function create()
    {
        $employees = employee::select('id', 'fullname')->get();

        return view('admin.checkout.create', compact('employees'));
    }

    public function store($request)
    {
        try {
            $checkout = Carbon::parse($request->checkout)->timestamp * 1000;
            $now = Carbon::now()->timestamp * 1000;

            $startOfDay = Carbon::parse($request->checkout)->startOfDay()->timestamp * 1000;
            $endOfDay = Carbon::parse($request->checkout)->endOfDay()->timestamp * 1000;

            $check = checkout::where('employeeId', $request->fullname)->whereBetween('checkout', [$startOfDay, $endOfDay])->first();

            $employee = employee::where('id', $request->fullname)->pluck('fullname')->first();

            if ($check) {
                return redirect()->back()->with('error', $employee . ' đã checkout hôm nay rồi!');
            }

            checkout::create([
                "employeeId" => $request->fullname,
                "checkout" => $checkout,
                "location" => $request->session()->get('fullname'),
                "latitude" => $request->latitude,
                "longtitude" => $request->longtitude,
                "meter" => $request->meter,
                "createdAt" => $now,
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Thêm checkout không thành công.');
        }

        return redirect()->back()->with('success', 'Thêm checkout thành công.');
    }
}
