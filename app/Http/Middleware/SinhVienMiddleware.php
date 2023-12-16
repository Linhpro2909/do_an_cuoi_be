<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SinhVienMiddleware
{

    public function handle(Request $request, Closure $next)
    {
        $auth = Auth::guard('sinh_vien')->check();
        if ($auth) {
            return $next($request);
        } else {
            return response()->json([
                'status'    => 0,
                'message'   => 'Vui lòng đăng nhập',
            ]);
        }
    }
}
