<?php

namespace App\ServicesAdmin\Employee;

use App\Models\department;
use App\Models\employee;
use App\Models\roles;

class findEmployee
{
    public function find($id)
    {
        $employee = employee::find($id);

        $department = department::select('id', 'name')->get();

        $role = roles::select('id', 'name')->get();

        $leaders = Employee::whereIn('roleId', [1, 2])->get();

        return view('admin.employee.find', compact('employee', 'role', 'department', 'leaders'));
    }
}
