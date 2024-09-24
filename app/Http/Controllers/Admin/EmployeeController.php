<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\createEmployeeRequest;
use App\Http\Requests\updateEmployeeRequest;
use App\ServicesAdmin\Employee\createEmployee;
use App\ServicesAdmin\Employee\findEmployee;
use App\ServicesAdmin\Employee\indexEmployee;
use App\ServicesAdmin\Employee\indexEmployeeRetired;
use App\ServicesAdmin\Employee\searchEmployee;
use App\ServicesAdmin\Employee\updateEmployee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    private $indexEmployee;
    private $indexEmployeeRetired;
    private $createEmployee;
    private $findEmployee;
    private $updateEmployee;
    private $searchEmployee;

    public function __construct(
        indexEmployee $indexEmployee,
        createEmployee $createEmployee,
        findEmployee $findEmployee,
        updateEmployee $updateEmployee,
        searchEmployee $searchEmployee,
        indexEmployeeRetired $indexEmployeeRetired,
    ) {
        $this->indexEmployee = $indexEmployee;
        $this->createEmployee = $createEmployee;
        $this->findEmployee = $findEmployee;
        $this->updateEmployee = $updateEmployee;
        $this->searchEmployee = $searchEmployee;
        $this->indexEmployeeRetired = $indexEmployeeRetired;
    }

    public function index()
    {
        return $this->indexEmployee->index();
    }

    public function create()
    {
        return $this->createEmployee->create();
    }

    public function store(createEmployeeRequest $request)
    {
        return $this->createEmployee->store($request);
    }

    public function find($id)
    {
        return $this->findEmployee->find($id);
    }

    public function update(updateEmployeeRequest $request, $id)
    {
        return $this->updateEmployee->update($request, $id);
    }

    public function search(Request $request)
    {
        return $this->searchEmployee->search($request);
    }

    public function indexRetired()
    {
        return $this->indexEmployeeRetired->index();
    }
}
