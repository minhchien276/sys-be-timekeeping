<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Checkout\createCheckout;
use App\Supports\Responder;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiCheckoutController extends Controller
{
    private $createCheckout;

    public function __construct(
        createCheckout $createCheckout,
    ) {
        $this->middleware('auth:api');
        $this->createCheckout = $createCheckout;
    }

    public function insert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employeeId' => 'required',
            'checkout' => 'required',
            'location' => 'required',
            'latitude' => 'required',
            'checkQr' => 'required',
            'longtitude' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
                'status' => false
            ], 400);
        }

        try {
            $checkout = $this->createCheckout->handle($request);
        } catch (Exception $e) {
            return Responder::fail(null, $e->getMessage());
        }

        return $checkout;
    }
}
