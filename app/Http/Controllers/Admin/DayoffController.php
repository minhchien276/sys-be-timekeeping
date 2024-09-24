<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\ServicesAdmin\Dayoff\indexDayoff;
use App\ServicesAdmin\Dayoff\searchDate;
use Illuminate\Http\Request;

class DayoffController extends Controller
{
    private $indexDayoff;
    private $searchDate;

    public function __construct(
        indexDayoff $indexDayoff,
        searchDate $searchDate,
    ) {
        $this->indexDayoff = $indexDayoff;
        $this->searchDate = $searchDate;
    }

    public function index()
    {
        return $this->indexDayoff->index();
    }

    public function searchDate(Request $request)
    {
        return $this->searchDate->handle($request);
    }
}
