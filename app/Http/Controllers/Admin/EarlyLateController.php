<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\ServicesAdmin\EarlyLated\indexEarlyLated;
use App\ServicesAdmin\EarlyLated\searchDate;
use Illuminate\Http\Request;

class EarlyLateController extends Controller
{
    private $indexEarlyLated;
    private $searchDate;

    public function __construct(
        indexEarlyLated $indexEarlyLated,
        searchDate $searchDate,
    ) {
        $this->indexEarlyLated = $indexEarlyLated;
        $this->searchDate = $searchDate;
    }

    public function index()
    {
        return $this->indexEarlyLated->index();
    }

    public function searchDate(Request $request)
    {
        return $this->searchDate->handle($request);
    }
}
