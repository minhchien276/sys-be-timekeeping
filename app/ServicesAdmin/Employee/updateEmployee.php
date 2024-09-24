<?php

namespace App\ServicesAdmin\Employee;

use App\Models\employee;
use Carbon\Carbon;
use Exception;

class updateEmployee
{
    public function update($request, $id)
    {
        $now = Carbon::now()->timestamp * 1000;

        try {
            $employee = Employee::findOrFail($id);

            $employee->update([
                'image' => $request->image,
                'fullname' => $request->fullname,
                'birthday' => $request->birthday,
                'identification' => $request->identification,
                'dayOff' => $request->dayOff,
                'email' => $request->email,
                'phone' => $request->phone,
                'salary' => $request->salary,
                'departmentId' => $request->departmentId,
                'roleId' => $request->roleId,
                'leaderId' => $request->leaderId,
                'status' => $request->status,
                'logged' => $request->logged,
                'updatedAt' => $now,
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Cập nhật nhân viên không thành công.');
        }

        return redirect()->back()->with('success', 'Cập nhật nhân viên thành công.');
    }
}
