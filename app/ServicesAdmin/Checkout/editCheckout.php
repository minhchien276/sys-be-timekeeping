<?php

namespace App\ServicesAdmin\Checkout;

use App\Models\checkout;
use Carbon\Carbon;
use Exception;

class editCheckout
{
    public function edit($id)
    {
        $checkout = checkout::leftJoin('employee', 'checkout.employeeId', '=', 'employee.id')
            ->where('checkout.id', $id)
            ->select('checkout.*', 'employee.image', 'employee.fullname', 'employee.email')
            ->first();

        $checkout->checkout = Carbon::createFromTimestamp($checkout->checkout / 1000)->toDateTimeLocalString();

        return view('admin.checkout.edit', compact('checkout'));
    }

    public function update($request, $id)
    {
        try {
            $checkout = Carbon::parse($request->checkout)->timestamp * 1000;
            $now = Carbon::now()->timestamp * 1000;

            checkout::where('id', $id)->update([
                "checkout" => $checkout,
                "location" => $request->session()->get('fullname'),
                "updatedAt" => $now,
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Cập nhật checkout không thành công.');
        }

        return redirect()->back()->with('success', 'Cập nhật checkout thành công.');
    }
}
