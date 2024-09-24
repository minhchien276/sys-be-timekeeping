<?php

namespace App\ServicesAdmin\Orders;

use App\Models\dosage;
use App\Supports\Responder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class createDosage
{
    public function handle($request)
    {
        $now = Carbon::now()->timestamp * 1000;

        $validator = Validator::make($request->all(), [
            'dosage' => 'required',
        ]);

        if ($validator->fails()) {
            return Responder::fail(null, $validator->errors()->first());
        }

        dosage::create([
            'dosageName' => $request->dosage,
            'createdAt' => $now,
        ]);

        return back();
    }
}
