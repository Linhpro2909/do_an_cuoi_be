<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{

    public function login(Request $request)
    {
        $auth = Auth::guard('admin')->attempt([
            'email'    => $request->email,
            'password' => $request->password,
        ]);
        if ($auth) {
            $user = Auth::guard('admin')->user();
            $token = $user->createToken('admin_token')->plainTextToken;
            return response()->json([
                'status'    => 1,
                'message'   => 'Đăng Nhập Thành Công!',
                'token'     => $token,
            ]);
        } else {
            return response()->json([
                'status'    => 0,
                'message'   => 'Thất bại',
            ]);
        }
    }

    public function checklogin(Request $request)
    {
        $user = auth('sanctum')->user();

        return response()->json([
            'user'  => $user
        ], 200);
    }

    public function Logout()
    {
        Auth::guard('admin')->logout();
        return response()->json([
            'status'    => 1,
            'message'   => "Đã đăng xuất thành công!"
        ]);
    }
}
