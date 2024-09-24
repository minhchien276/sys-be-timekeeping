<?php

namespace App\Http\Controllers\Api;

use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Models\checkin;
use App\Models\dayoff;
use App\Models\employee;
use App\Services\Checkin\createCheckin;
use App\Supports\Responder;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiCheckinController extends Controller
{
    private $createCheckin;

    public function __construct(
        createCheckin $createCheckin,
    ) {
        $this->middleware('auth:api');
        $this->createCheckin = $createCheckin;
    }

    public function insert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employeeId' => 'required',
            'checkin' => 'required',
            'location' => 'required',
            'latitude' => 'required',
            'longtitude' => 'required',
            'checkQr' => 'required',
            'createdAt' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
                'status' => false
            ], 400);
        }

        try {
            $checkin = $this->createCheckin->handle($request);
        } catch (Exception $e) {
            return Responder::fail(null, $e->getMessage());
        }

        return $checkin;
    }

    public function checkinAll()
    {
        try {
            $startOfDay = Carbon::today()->startOfDay()->timestamp * 1000;
            $endOfDay = Carbon::today()->endOfDay()->timestamp * 1000;

            $now = Carbon::now()->timestamp * 1000;

            $employeeCheckinToday = checkin::whereBetween('checkin', [$startOfDay, $endOfDay])
                ->pluck('employeeId')
                ->toArray();

            $employeeDayoffToday = dayoff::whereBetween('dayOffDate', [$startOfDay, $endOfDay])
                ->pluck('employeeId')
                ->toArray();

            $merged = (collect($employeeCheckinToday)->merge($employeeDayoffToday))->unique()->values()->all();;

            $employee = employee::where('roleId', '!=', RoleEnum::Director)
                ->where('status', 1)
                ->pluck('id')
                ->toArray();

            $data = collect($employee)->diff($merged)->values()->all();

            foreach ($data as $employeeId) {
                checkin::create([
                    "employeeId" => $employeeId,
                    "checkin" => 1722389400000,
                    "location" => '9, 9, Tây Hồ, Hà Nội, Việt Nam',
                    "latitude" => '9, 9, Tây Hồ, Hà Nội, Việt Nam',
                    "longtitude" => '9, 9, Tây Hồ, Hà Nội, Việt Nam',
                    "meter" => 1,
                    "createdAt" => $now,
                ]);
            }

            return true;
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Thêm checkin không thành công.');
        }

        return redirect()->back()->with('success', 'Thêm checkin thành công.');
    }

    public function deleteAll()
    {
        $startOfDay = Carbon::today()->startOfDay()->timestamp * 1000;
        $endOfDay = Carbon::today()->endOfDay()->timestamp * 1000;

        checkin::whereBetween('checkin', [$startOfDay, $endOfDay])->delete();
    }
}
