<?php

namespace App\ServicesAdmin\AuthAdmin;

use App\Models\employee;
use Illuminate\Support\Facades\Session;

class login
{
    public function login()
    {
        return view('admin.auth.login');
    }

    public function signIn($request)
    {
        try {
            $admin = employee::where('email', $request->email)
                ->where('employeeCode', $request->employeeCode)
                ->firstOrFail();

            if ($admin->status != 1) {
                return redirect()->back()->with('error', 'Bạn không có quyền truy cập!');
            }

            Session::put('user', $admin);
            Session::put('fullname', $admin->fullname);
            Session::put('departmentId', $admin->departmentId);
            Session::put('image', $admin->image);

            return redirect()->route('dashboard.index');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Đăng nhập thất bại!');
        }
    }
}
