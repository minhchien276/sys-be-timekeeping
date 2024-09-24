<?php

namespace App\ServicesAdmin\Orders;

use App\Models\type_medicine;
use App\Supports\Responder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class createTypeMedicine
{
    public function handle($request)
    {
        $now = Carbon::now()->timestamp * 1000;

        $validator = Validator::make($request->all(), [
            'medicineType' => 'required',
        ]);

        if ($validator->fails()) {
            return Responder::fail(null, $validator->errors()->first());
        }

        type_medicine::create([
            'type' => $request->medicineType,
            'createdAt' => $now,
        ]);

        return back();
    }
}
