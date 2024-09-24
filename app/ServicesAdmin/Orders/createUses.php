<?php

namespace App\ServicesAdmin\Orders;

use App\Models\uses;
use App\Supports\Responder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class createUses
{
    public function handle($request)
    {
        $now = Carbon::now()->timestamp * 1000;

        $validator = Validator::make($request->all(), [
            'uses' => 'required',
        ]);

        if ($validator->fails()) {
            return Responder::fail(null, $validator->errors()->first());
        }

        uses::create([
            'usesName' => $request->uses,
            'createdAt' => $now,
        ]);

        return back();
    }
}
