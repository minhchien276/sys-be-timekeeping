<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Application\Admin\getApproveApplication;
use App\Services\Application\Admin\getPendingApplication;
use App\Services\Application\createApplication;
use App\Services\Application\getApplicationDetails;
use App\Services\Application\getByApproveId;
use App\Services\Application\getByCompany;
use App\Services\Application\getByDepartment;
use App\Services\Application\getById;
use App\Services\Application\getReceiver;
use App\Services\Application\updateStatus;
use Illuminate\Http\Request;

class ApiApplicationController extends Controller
{
    private $getById;
    private $getByDepartment;
    private $getByCompany;
    private $createApplication;
    private $getReceiver;
    private $updateStatus;
    private $getByApproveId;
    private $getApplicationDetails;
    private $getApproveApplication;
    private $getPendingApplication;

    public function __construct(
        getById $getById,
        getByDepartment $getByDepartment,
        getByCompany $getByCompany,
        createApplication $createApplication,
        getReceiver $getReceiver,
        updateStatus $updateStatus,
        getByApproveId $getByApproveId,
        getApplicationDetails $getApplicationDetails,
        getApproveApplication $getApproveApplication,
        getPendingApplication $getPendingApplication,
    ) {
        $this->middleware('auth:api');
        $this->getById = $getById;
        $this->getByDepartment = $getByDepartment;
        $this->getByCompany = $getByCompany;
        $this->createApplication = $createApplication;
        $this->getReceiver = $getReceiver;
        $this->updateStatus = $updateStatus;
        $this->getByApproveId = $getByApproveId;
        $this->getApplicationDetails = $getApplicationDetails;
        $this->getApproveApplication = $getApproveApplication;
        $this->getPendingApplication = $getPendingApplication;
    }

    public function create(Request $request)
    {
        return $this->createApplication->handle($request);
    }

    public function getById(Request $request)
    {
        return $this->getById->handle($request);
    }

    public function getByDepartment($employeeId, $departmentId)
    {
        return $this->getByDepartment->handle($employeeId, $departmentId);
    }

    public function getByCompany($employeeId)
    {
        return $this->getByCompany->handle($employeeId);
    }

    public function getReceiver($employeeId)
    {
        return $this->getReceiver->handle($employeeId);
    }

    public function updateStatus(Request $request)
    {
        return $this->updateStatus->handle($request);
    }

    public function getByApproveId(Request $request)
    {
        return $this->getByApproveId->handle($request);
    }

    public function getApplicationDetails($applicationId)
    {
        return $this->getApplicationDetails->handle($applicationId);
    }

    public function getPendingApplication(Request $request)
    {
        return $this->getPendingApplication->handle($request);
    }

    public function getApproveApplication(Request $request)
    {
        return $this->getApproveApplication->handle($request);
    }

    public function getApplications(Request $request){
        return $this->getById->getApplications($request);
    }
}
