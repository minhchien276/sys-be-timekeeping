<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\employee;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiAuthConttroller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'refreshToken']]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Vui lòng nhập đủ thông tin',
                'status' => false
            ], 400);
        }

        $credentials = [
            'email' => $request->email,
            'password' => 123456,
        ];

        $token = Auth::attempt($credentials);

        if (!$token) {
            return response()->json([
                'status' => false,
                'message' => 'Thông tin đăng nhập không hợp lệ',
            ], 403);
        }

        $employee = Auth::user();

        if ($employee->status == 0 || $employee->logged == 1) {
            return response()->json([
                'message' => 'Bạn đã hết quyền try cập',
                'status' => false,
                'data' => [],
            ], 403);
        }

        employee::where('id', $employee->id)->update(['logged' => 1]);

        if ($request->device_token != null) {
            employee::where('id', $employee->id)->update(['device_token' => $request->device_token]);
        }

        $refreshToken = $this->createRefreshToken();

        return $this->responseWithToken($token, $refreshToken, $employee);
    }

    public function refreshToken(Request $request)
    {
        $refreshToken = $request->refresh_token;

        try {
            $decoded = JWTAuth::getJWTProvider()->decode($refreshToken);

            // lay thong tin employee
            $employee = employee::find($decoded['employee_id']);

            if (!$employee) {
                return response()->json(['message' => 'Thông tin đăng nhập không hợp lệ'], 404);
            }

            //cap lai token moi
            $token = auth('api')->login($employee);

            $refreshToken = $this->createRefreshToken();

            return $this->responseWithToken($token, $refreshToken, $employee);
        } catch (Exception $e) {
            return response()->json(['message' => 'Phiên đăng nhập đã hết hạn'], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            // Adds token to blacklist.
            $forever = true;
            Auth::parseToken()->invalidate($forever);

            return response()->json([
                'status'   => false,
                'message' => 'Đăng xuất thành công'
            ]);
        } catch (TokenExpiredException $exception) {
            return response()->json([
                'status'   => true,
                'message' => 'Phiên đăng nhập đã hết hạn'

            ], 401);
        } catch (TokenInvalidException $exception) {
            return response()->json([
                'status'   => true,
                'message' => 'Phiên đăng nhập không tồn tại'
            ], 401);
        } catch (JWTException $exception) {
            return response()->json([
                'status'   => true,
                'message' => 'Phiên đăng nhập không tồn tại'
            ], 500);
        }
    }

    private function responseWithToken($token, $refreshToken, $employee)
    {
        return response()->json([
            'access_token' => $token,
            'refresh_token' => $refreshToken,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60,
            'data' => $employee,
            'message' => 'Đăng nhập thành công',
            'status' => true,
        ]);
    }

    private function createRefreshToken()
    {
        $data = [
            'employee_id' => auth('api')->user()->id,
            'random' => rand() . time(),
            'exp' => time() + config('jwt.refresh_ttl'),
        ];

        $refreshToken = JWTAuth::getJWTProvider()->encode($data);

        return $refreshToken;
    }
}
