<?php

namespace App\ServicesAdmin\Orders;

use App\Models\advice;
use App\Supports\Responder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class createAdvice
{
    public function handle($request)
    {
        $now = Carbon::now()->timestamp * 1000;

        $validator = Validator::make($request->all(), [
            'advice' => 'required',
        ]);

        if ($validator->fails()) {
            return Responder::fail(null, $validator->errors()->first());
        }

        advice::create([
            'adviceName' => $request->advice,
            'createdAt' => $now,
        ]);

        return back();
    }
}
