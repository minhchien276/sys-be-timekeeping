<?php

namespace App\ServicesAdmin\Checkin;

use App\Models\checkin;
use App\Models\employee;
use Carbon\Carbon;
use Exception;

class createCheckin
{
    public function create()
    {
        $employees = employee::select('id', 'fullname')->get();

        return view('admin.checkin.create', compact('employees'));
    }

    public function store($request)
    {
        try {
            $checkin = Carbon::parse($request->checkin)->timestamp * 1000;
            $startOfDay = Carbon::parse($request->checkin)->startOfDay()->timestamp * 1000;
            $endOfDay = Carbon::parse($request->checkin)->endOfDay()->timestamp * 1000;

            $now = Carbon::now()->timestamp * 1000;

            $check = checkin::where('employeeId', $request->fullname)->whereBetween('checkin', [$startOfDay, $endOfDay])->first();

            $employee = employee::where('id', $request->fullname)->pluck('fullname')->first();

            if ($check) {
                return redirect()->back()->with('error', $employee . ' đã checkin hôm nay rồi!');
            }

            checkin::create([
                "employeeId" => $request->fullname,
                "checkin" => $checkin,
                "location" => $request->session()->get('fullname'),
                "latitude" => $request->latitude,
                "longtitude" => $request->longtitude,
                "meter" => $request->meter,
                "createdAt" => $now,
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Thêm checkin không thành công.');
        }

        return redirect()->back()->with('success', 'Thêm checkin thành công.');
    }
}
