<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\OverTime\getOverTime;
use Illuminate\Http\Request;

class ApiOverTimeController extends Controller
{
    private $getOverTime;

    public function __construct(getOverTime $getOverTime)
    {
        $this->middleware("auth:api");
        $this->getOverTime = $getOverTime;
    }

    public function getOverTime(Request $request)
    {
        return $this->getOverTime->handle($request);
    }
}
