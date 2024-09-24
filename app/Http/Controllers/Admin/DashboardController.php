<?php

namespace App\Http\Controllers\Admin;

use App\Enums\TypeApplication;
use App\Http\Controllers\Controller;
use App\Models\application;
use App\Models\blog;
use App\Models\dayoff;
use App\Models\employee;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $startOfDay = Carbon::now()->startOfDay()->timestamp * 1000;
        $endOfDay = Carbon::now()->endOfDay()->timestamp * 1000;

        // tổng số lượng nhân sự
        $employee = employee::where('status', 1)->count();

        // tổng số lượng bài viết
        $blog = blog::count();

        // tổng số lượng đơn từ
        $application = application::count();

        // Số lượng đơn nghỉ phép
        $dayoff = application::where('type', TypeApplication::PaidLeave)->orWhere('type', TypeApplication::UnpaidLeave)->count();
        // Số lượng đơn tăng ca
        $overtime = application::where('type', TypeApplication::OverTime)->count();
        // Số lượng đơn xin đến muộn về sớm
        $early_lated = application::where('type', TypeApplication::Early)->orWhere('type', TypeApplication::Lated)->count();

        // Số lượng người nghỉ phép hôm nay
        $dayoffToday = dayoff::whereBetween('dayOffDate', [$startOfDay, $endOfDay])->count();
        $dayoffAllday = dayoff::whereBetween('dayOffDate', [$startOfDay, $endOfDay])->where('session', 0)->count(); // nghỉ phép cả ngày
        $dayoffMorning = dayoff::whereBetween('dayOffDate', [$startOfDay, $endOfDay])->where('session', 1)->count(); // Nghỉ phép buổi sáng
        $dayoffAfternoon = dayoff::whereBetween('dayOffDate', [$startOfDay, $endOfDay])->where('session', 2)->count(); // Nghỉ phép buổi chiều


        return view('admin.dashboard.dashboard')->with([
            'employee' => $employee,
            'blog' => $blog,
            'application' => $application,
            'dayoff' => $dayoff,
            'overtime' => $overtime,
            'early_lated' => $early_lated,
            'dayoffToday' => $dayoffToday,
            'dayoffAllday' => $dayoffAllday,
            'dayoffMorning' => $dayoffMorning,
            'dayoffAfternoon' => $dayoffAfternoon,
        ]);
    }
}
