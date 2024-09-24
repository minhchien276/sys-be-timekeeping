<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Salary\calculateSalary;
use App\Services\Salary\getLastSalary;
use App\Services\Salary\getSalary;
use App\Services\Salary\getSalaryByNotification;
use Illuminate\Http\Request;

class ApiSalaryController extends Controller
{
    private $getSalary;
    private $calculateSalary;
    private $getLastSalary;
    private $getSalaryByNotification;

    public function __construct(
        getSalary $getSalary,
        calculateSalary $calculateSalary,
        getLastSalary $getLastSalary,
        getSalaryByNotification $getSalaryByNotification,
    ) {
        $this->middleware("auth:api");
        $this->getSalary = $getSalary;
        $this->calculateSalary = $calculateSalary;
        $this->getLastSalary = $getLastSalary;
        $this->getSalaryByNotification = $getSalaryByNotification;
    }

    public function getSalary()
    {
        return $this->getSalary->handle();
    }

    public function calculateSalary(Request $request)
    {
        return $this->calculateSalary->handle($request);
    }

    public function getLastSalary()
    {
        return $this->getLastSalary->handle();
    }

    public function getSalaryByNotification(Request $request)
    {
        return $this->getSalaryByNotification->handle($request);
    }
}
