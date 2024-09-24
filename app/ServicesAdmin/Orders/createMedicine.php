<?php

namespace App\ServicesAdmin\Orders;

use App\Models\medicine;
use App\Supports\Responder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class createMedicine
{
    public function handle($request)
    {
        $now = Carbon::now()->timestamp * 1000;

        $validator = Validator::make($request->all(), [
            'medicineName' => 'required',
        ]);

        if ($validator->fails()) {
            return Responder::fail(null, $validator->errors()->first());
        }

        medicine::create([
            'medicineName' => $request->medicineName,
            'createdAt' => $now,
        ]);

        return back();
    }
}
