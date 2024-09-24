<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Exam\beginTest;
use App\Services\Exam\getAllTests;
use App\Services\Exam\getEmployeeTests;
use App\Services\Exam\getTestDetails;
use App\Services\Exam\getTestsScore;
use App\Services\Exam\saveEmployeeTest;
use Illuminate\Http\Request;

class ApiTestController extends Controller
{
    private $getAllTests;
    private $getTestDetails;
    private $getEmployeeTests;
    private $saveEmployeeTest;
    private $getTestsScore;
    private $beginTest;

    public function __construct(
        getAllTests $getAllTests,
        getTestDetails $getTestDetails,
        getEmployeeTests $getEmployeeTests,
        saveEmployeeTest $saveEmployeeTest,
        getTestsScore $getTestsScore,
        beginTest $beginTest,
    ) {
        $this->middleware("auth:api");
        $this->getAllTests = $getAllTests;
        $this->getTestDetails = $getTestDetails;
        $this->getEmployeeTests = $getEmployeeTests;
        $this->saveEmployeeTest = $saveEmployeeTest;
        $this->getTestsScore = $getTestsScore;
        $this->beginTest = $beginTest;
    }

    public function getAllTests()
    {
        return $this->getAllTests->handle();
    }

    public function getTestDetails($employeeTestId)
    {
        return $this->getTestDetails->handle($employeeTestId);
    }

    public function getEmployeeTests()
    {
        return $this->getEmployeeTests->handle();
    }

    public function saveEmployeeTest(Request $request)
    {
        return $this->saveEmployeeTest->handle($request);
    }

    public function getTestsScore($testId)
    {
        return $this->getTestsScore->handle($testId);
    }

    public function beginTest($employeeTestId)
    {
        return $this->beginTest->handle($employeeTestId);
    }
}
