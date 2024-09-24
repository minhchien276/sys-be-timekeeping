<?php

namespace App\Services\ParseToken;

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class ParseToken
{
    public function handle()
    {
        try {
            // Giải mã token
            $decodedToken = JWTAuth::parseToken()->authenticate();

            return $decodedToken;
        } catch (JWTException $e) {
            return response()->json(['error' => 'Failed to decode token'], 401);
        }
    }
}
