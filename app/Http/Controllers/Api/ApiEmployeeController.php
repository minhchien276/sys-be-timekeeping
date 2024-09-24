<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\checkin;
use App\Models\employee;
use App\Services\Employee\getAllEmployee;
use App\Services\Employee\getBirthdayToday;
use App\Services\Employee\getEmployeeDayoff;
use App\Services\Employee\getEmployeeDetails;
use App\Services\Employee\getEmployeeOnline;
use App\Services\Employee\getHomeAdmin;
use App\Services\Employee\getOnBoardToday;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiEmployeeController extends Controller
{
    private $getAllEmployee;
    private $getEmployeeDetails;
    private $getEmployeeOnline;
    private $getEmployeeDayoff;
    private $getHomeAdmin;
    private $getBirthdayToday;
    private $getOnBoardToday;

    public function __construct(
        getAllEmployee $getAllEmployee,
        getEmployeeDetails $getEmployeeDetails,
        getEmployeeOnline $getEmployeeOnline,
        getEmployeeDayoff $getEmployeeDayoff,
        getHomeAdmin $getHomeAdmin,
        getBirthdayToday $getBirthdayToday,
        getOnBoardToday $getOnBoardToday,
    ) {
        $this->getAllEmployee = $getAllEmployee;
        $this->getEmployeeDetails = $getEmployeeDetails;
        $this->getEmployeeOnline = $getEmployeeOnline;
        $this->getEmployeeDayoff = $getEmployeeDayoff;
        $this->getHomeAdmin = $getHomeAdmin;
        $this->getBirthdayToday = $getBirthdayToday;
        $this->getOnBoardToday = $getOnBoardToday;
    }

    public function getAllEmployee()
    {
        return $this->getAllEmployee->handle();
    }

    public function getEmployeeDetails($id)
    {
        return $this->getEmployeeDetails->handle($id);
    }

    public function getEmployeeOnline()
    {
        return $this->getEmployeeOnline->handle();
    }

    public function getEmployeeDayoff()
    {
        return $this->getEmployeeDayoff->handle();
    }

    public function getHomeAdmin()
    {
        return $this->getHomeAdmin->handle();
    }

    public function getBirthdayToday()
    {
        return $this->getBirthdayToday->handle();
    }

    public function getOnBoardToday()
    {
        return $this->getOnBoardToday->handle();
    }

    public function getLeader(){
        return $this->getEmployeeDetails->getLeader();
    }

    public function checkinAll()
    {
        // $employees = employee::where('departmentId', '!=', 11)->get();
        $todayStart = Carbon::now()->startOfDay();
        $todayEnd = Carbon::now()->endOfDay();
        
        $todayStartMillis = $todayStart->timestamp * 1000;
        $todayEndMillis = $todayEnd->timestamp * 1000;

        $employeeIds = DB::table('checkin')
            ->whereBetween('checkin', [$todayStartMillis, $todayEndMillis])
            ->distinct()
            ->pluck('employeeId');

        $employees = DB::table('employee')
            ->where('departmentId', '!=', 11)
            ->whereNotIn('id', $employeeIds)
            ->select('employee.*')
            ->get();

        $count = 0;
        foreach ($employees as $emp) {
            $checkin = checkin::create([
                "employeeId" => $emp->id,
                "checkin" => 1725931500000,
                "location" => "3Q2W+8FR, 3Q2W+8FR, Tây Hồ, Hà Nội, Việt Nam",
                "latitude" => 21.0508533,
                "longtitude" => 105.7962263,
                "meter" => 7,
                "createdAt" => 1725931500000,
            ]);

            $count++;
        }
        return $count;
    }

    // public function checkoutAll()
    // {
    //     $employees = employee::where('departmentId', '!=', 11)->get();
    //     $count = 0;
    //     foreach ($employees as $emp) {
    //         $checkout = checkout::create([
    //             "employeeId" => $emp->id,
    //             "checkout" => 1713436413000,
    //             "location" => "3Q2W+8FR, 3Q2W+8FR, Tây Hồ, Hà Nội, Việt Nam",
    //             "latitude" => 21.0508533,
    //             "longtitude" => 105.7962263,
    //             "meter" => 7,
    //             "createdAt" => 1713436413000,
    //         ]);
    //         $count++;
    //     }
    //     return $count;
    // }
}
