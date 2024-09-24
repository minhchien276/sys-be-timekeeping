<?php

namespace App\Services\Auth;

use App\Models\employee;
use Illuminate\Support\Str;

class createEmployee
{
    public function handle($request)
    {
        $employee = employee::create([
            'employeeCode' => Str::uuid(),
            'fullname' => $request->fullname,
            'birthday' => $request->birthday,
            'identification' => $request->identification,
            'salary' => $request->salary,
            'dayOff' => $request->dayOff,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt(123456),
            'status' => $request->status,
            'departmentId' => $request->departmentId,
            'roleId' => $request->roleId,
            'createdAt' => $request->createdAt,
        ]);

        return $employee;
    }
}
