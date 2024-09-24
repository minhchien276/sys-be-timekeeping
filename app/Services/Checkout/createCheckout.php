<?php

namespace App\Services\Checkout;

use App\Models\checkin;
use App\Models\checkout;
use App\Services\Employee\getEmployeeDetails;
use App\Supports\Responder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class createCheckout
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

        $Comp = DB::table('company')->orderBy('id', 'desc')->first();

        $meter = $this->calculateDistanceInMeters($Comp->latitude, $Comp->longtitude, $request->latitude, $request->longtitude);

        $checkin = checkin::where('employeeId', $request->employeeId)
            ->whereBetween('createdAt', [$startOfDay, $endOfDay])
            ->first();

        if (!$checkin) {
            return Responder::fail(null, 'Bạn chưa checkin', 400);
        }

        $check = checkout::where('employeeId', $request->employeeId)
            ->whereBetween('createdAt', [$startOfDay, $endOfDay])
            ->first();

        if ($Comp->qrCode == $request->checkQr) {
            if ($check) {
                $checkout = checkout::where('id', $check->id)
                    ->update([
                        'checkout' => $request->checkout,
                        'location' => $request->location,
                        'latitude' => $request->latitude,
                        'longtitude' => $request->longtitude,
                        'meter' => $meter,
                        'updatedAt' => $now,
                    ]);

                if ($checkout) {
                    return $this->getEmployeeDetails->handle($request->employeeId);
                }

                return Responder::fail(null, 'checkout update failed');
            } else {
                $checkout = checkout::create([
                    'employeeId' => $request->employeeId,
                    'checkout' => $request->checkout,
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
    }
}
