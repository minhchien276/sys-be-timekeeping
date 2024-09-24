<?php

namespace App\ServicesAdmin\Employee;

use App\Models\employee;

class indexEmployee
{
    public function index()
    {
        $key_search = '';

        $employees = employee::where('status', 1)->get();

        return view('admin.employee.index', compact('employees', 'key_search'));
    }
}
