<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\employee;
use App\Services\Calendar\calendar;
use App\Services\ListDayOfDate\getTwoMonth;
use App\Supports\Responder;
use Exception;
use Illuminate\Http\Request;

class ApiCalendarController extends Controller
{
    private $calendar;
    private $getTwoMonth;

    public function __construct(
        calendar $calendar,
        getTwoMonth $getTwoMonth,
    ) {
        $this->middleware('auth:api');
        $this->calendar = $calendar;
        $this->getTwoMonth = $getTwoMonth;
    }

    public function calendar(Request $request)
    {
        $listDate = $this->getTwoMonth->handle();
        $employee = employee::where('id', $request->id)->get();
        try {
            $current = $this->calendar->handle($request, $listDate[0]);
            $current = $this->calendar->earlyLated($listDate[0], $request->id, $current);
            $current = $this->calendar->calculateDayOff($listDate[0], $request->id, $current);
            $previous = $this->calendar->handle($request, $listDate[1]);
            $previous = $this->calendar->earlyLated($listDate[1], $request->id, $previous);
            $previous = $this->calendar->calculateDayOff($listDate[1], $request->id, $previous);
        } catch (Exception $e) {
            return Responder::fail(null, $e->getMessage());
        }

        return response()->json([
            "current" => $current,
            "previous" => $previous,
            "employee" => $employee,
            "message" => "Danh sách chấm công",
            "status" => true
        ], 200);
    }
}
