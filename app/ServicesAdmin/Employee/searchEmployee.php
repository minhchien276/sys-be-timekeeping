<?php

namespace App\ServicesAdmin\Employee;

use App\Models\employee;

class searchEmployee
{
    public function search($request)
    {
        $key_search = $request->key_search;

        $employees = employee::where('status', 1)->where('email', 'like', '%' . $key_search . '%')->get();

        return view('admin.employee.index', compact('employees', 'key_search'));
    }
}
