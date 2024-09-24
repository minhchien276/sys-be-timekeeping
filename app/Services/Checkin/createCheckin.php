<?php

namespace App\Services\Checkin;

use App\Models\checkin;
use App\Services\Employee\getEmployeeDetails;
use App\Supports\Responder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class createCheckin
{
    private $getEmployeeDetails;

    public function __construct(
        getEmployeeDetails $getEmployeeDetails
    ) {
        $this->getEmployeeDetails = $getEmployeeDetails;
    }

    public function calculateDistanceInMeters($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;

        return $distance;
    }

    public function handle($request)
    {
        $startOfDay = Carbon::now()->startOfDay()->timestamp * 1000;
        $endOfDay = Carbon::now()->endOfDay()->timestamp * 1000;
        $now = Carbon::now()->timestamp * 1000;

        $check = checkin::where('employeeId', $request->employeeId)
            ->whereBetween('createdAt', [$startOfDay, $endOfDay])
            ->first();

        try {
            $Comp = DB::table('company')->orderBy('id', 'desc')->first();

            $meter = $this->calculateDistanceInMeters($Comp->latitude, $Comp->longtitude, $request->latitude, $request->longtitude);

            if ($Comp->qrCode == $request->checkQr) {
                if ($check) {
                    return Responder::fail(null, 'Bạn đã chấm công rồi', 400);
                } else {
                    checkin::create([
                        'employeeId' => $request->employeeId,
                        'checkin' => $request->checkin,
                        'location' => $request->location,
                        'latitude' => $request->latitude,
                        'longtitude' => $request->longtitude,
                        'meter' => $meter,
                        'createdAt' => $now,
                    ]);

                    return $this->getEmployeeDetails->handle($request->employeeId);
                }
            } else {
                return Responder::fail(null, 'Mã Qr không hợp lệ', 400);
            }
        } catch (\Exception $e) {
            return Responder::fail(null, 'Chấm công thất bại', 400);
        }
    }
}
