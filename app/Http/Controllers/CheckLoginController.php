<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckLoginController extends Controller
{
    public function checklogin(Request $request)
    {
        $user = auth('sanctum')->user();
        if($user) {
            return response()->json([
                'user'  => $user
            ], 200);
        } else {
            return response()->json([
            ], 401);
        }
    }

    public function Logout()
    {
        Auth::guard('sinh_vien')->logout();
        return response()->json([
            'status'    => 1,
            'message'   => "Đã đăng xuất thành công!"
        ]);
    }
}
