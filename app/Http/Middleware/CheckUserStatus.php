<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $userStatus = Auth::user()->status;

            if ($userStatus == 1) {
                return $next($request);
            } else {
                return response()->json(['error' => 'Bạn không có quyền truy cập'], 403);
            }
        }

        return response()->json(['error' => 'Bạn chưa đăng nhập']);
    }
}
