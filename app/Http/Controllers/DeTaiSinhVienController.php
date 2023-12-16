<?php

namespace App\Http\Controllers;

use App\Models\DeTaiSinhVien;
use App\Models\Nhom;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class DeTaiSinhVienController extends Controller
{
    public function createData(Request $request)
    {
        $data = $request->all();
        $id_user = $request->id_user;
        $nhom = Nhom::where('id_sinh_vien', $id_user)->first(); //mã nhóm
        $data['ma_nhom'] = $nhom->ma_nhom;
        $data['ten_nhom'] = $nhom->ten_nhom;
        $check = DeTaiSinhVien::where('ma_nhom', $nhom->ma_nhom)->where('tinh_trang', 0)->orWhere('tinh_trang', 1)->first();
        if(!$check) {
            // Kiểm tra tên đề tài trùng khoảng 80%
            $tenDeTaiMoi = $data['ten_de_tai'];
            $tenDeTaiDaTonTai = DeTaiSinhVien::where('ma_nhom', $data['ma_nhom'])
                                            ->pluck('ten_de_tai')
                                            ->toArray();

            foreach ($tenDeTaiDaTonTai as $tenDaTonTai) {
                similar_text($tenDeTaiMoi, $tenDaTonTai, $percent);
                if ($percent >= 80) {
                    return response()->json([
                        'status'  => 0,
                        'message' => 'Tên đề tài của bạn dường như đã trùng lặp với một đề tài khác.',
                    ]);
                }
            }

            // Nếu không có sự trùng lặp, tạo đề tài mới
            DeTaiSinhVien::create($data);

            return response()->json([
                'status'  => 1,
                'message' => 'Đã tạo đề tài thành công!',
            ]);
        } else {
            if($check->tinh_trang == 2) {
                // Kiểm tra tên đề tài trùng khoảng 80%
                $tenDeTaiMoi = $data['ten_de_tai'];
                $tenDeTaiDaTonTai = DeTaiSinhVien::where('ma_nhom', $data['ma_nhom'])
                                                ->pluck('ten_de_tai')
                                                ->toArray();

                foreach ($tenDeTaiDaTonTai as $tenDaTonTai) {
                    similar_text($tenDeTaiMoi, $tenDaTonTai, $percent);
                    if ($percent >= 80) {
                        return response()->json([
                            'status'  => 0,
                            'message' => 'Tên đề tài của bạn dường như đã trùng lặp với một đề tài khác.',
                        ]);
                    }
                }

                // Nếu không có sự trùng lặp, tạo đề tài mới
                DeTaiSinhVien::create($data);

                return response()->json([
                    'status'  => 1,
                    'message' => 'Đã tạo đề tài thành công!',
                ]);
            } else if($check->tinh_trang == 0 || $check->tinh_trang == 1) {
                return response()->json([
                    'status'  => 0,
                    'message' => 'Nhóm đã có đề tài!',
                ]);
            }
        }
    }
    public function getData()
    {
        $data = DeTaiSinhVien::get();
        return response()->json([
            'status'    => 1,
            'data'      => $data,
        ]);
    }
    public function trangthai(Request $request)
    {

        $de_tai = DeTaiSinhVien::where('id', $request->id)->first();

        $now = Carbon::today();

        if($de_tai){
            if($request->thoi_gian_ket_thuc != null) {
                if ($now->greaterThan(Carbon::parse($request->thoi_gian_ket_thuc))) {
                    return response()->json([
                        'status'    => 0,
                        'message'   => 'Không được duyệt đề tài!',
                    ]);
                } else {
                    $de_tai->tinh_trang         = !$de_tai->tinh_trang;
                    $de_tai->thoi_gian_ket_thuc = $request->thoi_gian_ket_thuc;
                    $de_tai->save();
                    return response()->json([
                        'status'    => 1,
                        'message'   => 'Đã duyệt đề tài thành công!',
                    ]);
                }
            } else {
                return response()->json([
                    'status'    => 0,
                    'message'   => 'Chưa nhập thời gian kết thúc!',
                ]);
            }
        }
    }

    public function capNhatTime(Request $request)
    {
        $de_tai = DeTaiSinhVien::where('id', $request->id)->first();

        $now = Carbon::today();

        if($de_tai){
            if($request->thoi_gian_ket_thuc != null) {
                if ($now->greaterThan(Carbon::parse($request->thoi_gian_ket_thuc))) {
                    return response()->json([
                        'status'    => 0,
                        'message'   => 'Không được duyệt đề tài!',
                    ]);
                } else {
                    $de_tai->thoi_gian_ket_thuc = $request->thoi_gian_ket_thuc;
                    $de_tai->save();
                    return response()->json([
                        'status'    => 1,
                        'message'   => 'Đã duyệt đề tài thành công!',
                    ]);
                }
            } else {
                return response()->json([
                    'status'    => 0,
                    'message'   => 'Chưa nhập thời gian kết thúc!',
                ]);
            }
        }
    }

    public function huyDeTai(Request $request)
    {

        $de_tai = DeTaiSinhVien::where('id', $request->id)->first();
        if($de_tai){
            $de_tai->tinh_trang = !$de_tai->tinh_trang;
            $de_tai->thoi_gian_ket_thuc = null;
            $de_tai->save();
        }
    }

    public function xoaDeTai(Request $request)
    {

        $de_tai = DeTaiSinhVien::where('id', $request->id)->first();
        if($de_tai){
            $de_tai->tinh_trang = 2;
            $de_tai->save();
        }
    }

    public function editDeTai(Request $request)
    {
        $data = $request->all();
        $id_user = $request->id_user;
        $nhom = Nhom::where('id_sinh_vien', $id_user)->first(); //mã nhóm
        if($request->ma_nhom == $nhom->ma_nhom) {
            $now = Carbon::today();
            $de_tai = DeTaiSinhVien::where('id', $request->id)->first();
            if ($de_tai && $now->lessThanOrEqualTo($de_tai->thoi_gian_ket_thuc)) {
                $de_tai->ten_de_tai = $request->ten_de_tai;
                $de_tai->save();
                return response()->json([
                    'status' => 1,
                    'message' => 'Đã đổi đề tài thành công!',
                ]);
            } else {
                return response()->json([
                    'status' => 0,
                    'message' => 'Không thể cập nhật đề tài này do hết hạn hoặc không tìm thấy đề tài.',
                ]);
            }
        } else {
            return response()->json([
                'status'    => 0,
                'message'   => 'Đề tài này không thuộc về nhóm bạn!',
            ]);
        }
    }
}
