<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Document\createDocument;
use App\Services\Document\getByCompany;
use App\Services\Document\getByDepartment;
use App\Services\Document\getById;
use Illuminate\Http\Request;

class ApiDocumentController extends Controller
{
    private $getById;
    private $getByDepartment;
    private $getByCompany;
    private $createDocument;

    public function __construct(
        getById $getById,
        getByDepartment $getByDepartment,
        getByCompany $getByCompany,
        createDocument $createDocument,
    ) {
        $this->middleware('auth:api');
        $this->getById = $getById;
        $this->getByDepartment = $getByDepartment;
        $this->getByCompany = $getByCompany;
        $this->createDocument = $createDocument;
    }

    public function getById($employeeId)
    {
        return $this->getById->handle($employeeId);
    }

    public function getByDepartment($employeeId, $departmentId)
    {
        return $this->getByDepartment->handle($employeeId, $departmentId);
    }

    public function getByCompany($employeeId)
    {
        return $this->getByCompany->handle($employeeId);
    }

    public function create(Request $request)
    {
        return $this->createDocument->handle($request);
    }
}
