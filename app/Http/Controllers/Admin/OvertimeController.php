<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\ServicesAdmin\Overtime\indexOvertime;
use App\ServicesAdmin\Overtime\searchDate;
use Illuminate\Http\Request;

class OvertimeController extends Controller
{
    private $indexOvertime;
    private $searchDate;

    public function __construct(
        indexOvertime $indexOvertime,
        searchDate $searchDate,
    ) {
        $this->indexOvertime = $indexOvertime;
        $this->searchDate = $searchDate;
    }

    public function index()
    {
        return $this->indexOvertime->index();
    }

    public function searchDate(Request $request)
    {
        return $this->searchDate->handle($request);
    }
}
