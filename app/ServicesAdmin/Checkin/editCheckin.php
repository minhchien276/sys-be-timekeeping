<?php

namespace App\ServicesAdmin\Checkin;

use App\Models\checkin;
use Carbon\Carbon;
use Exception;

class editCheckin
{
    public function edit($id)
    {
        $checkin = checkin::leftJoin('employee', 'checkin.employeeId', '=', 'employee.id')
            ->where('checkin.id', $id)
            ->select('checkin.*', 'employee.image', 'employee.fullname', 'employee.email')
            ->first();

        $checkin->checkin = Carbon::createFromTimestamp($checkin->checkin / 1000)->toDateTimeLocalString();

        return view('admin.checkin.edit', compact('checkin'));
    }

    public function update($request, $id)
    {
        try {
            $checkin = Carbon::parse($request->checkin)->timestamp * 1000;
            $now = Carbon::now()->timestamp * 1000;

            checkin::where('id', $id)->update([
                "checkin" => $checkin,
                "location" => $request->session()->get('fullname'),
                "updatedAt" => $now,
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Cập nhật checkin không thành công.');
        }

        return redirect()->back()->with('success', 'Cập nhật checkin thành công.');
    }
}
