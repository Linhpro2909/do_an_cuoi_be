<?php

namespace App\Http\Middleware;

use App\Models\Admin;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{

    public function handle(Request $request, Closure $next)
    {
        $auth = Auth::guard('admin')->check();
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
