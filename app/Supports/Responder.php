<?php

namespace App\Supports;

class Responder
{
    public static function success($data, string $message, int $statusCode = 200)
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
            'status' => true
        ], $statusCode);
    }

    public static function fail($data, $message, int $statusCode = 400)
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
            'status' => false
        ], $statusCode);
    }
}
