<?php

namespace App\ServicesAdmin\Employee;

use App\Models\employee;

class indexEmployeeRetired
{
    public function index()
    {
        $employees = employee::where('status', 0)->get();

        return view('admin.employee.index_retired', compact('employees'));
    }
}
