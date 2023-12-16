<?php

namespace App\Http\Controllers;

use App\Models\SinhVien;
use App\Models\TmpNhom;
use Illuminate\Http\Request;

class TmpNhomController extends Controller
{
    public function getData()
    {
        // Lấy tất cả dữ liệu mà ko có điều kiện gì cả
        $data   =   TmpNhom::join('sinh_viens', 'sinh_viens.id', 'tmp_nhoms.id_sinh_vien')->select('tmp_nhoms.*', 'sinh_viens.tinh_trang')->get();
        return response()->json([
            'data'      => $data,
        ]);
    }
    public function createData(Request $request)
    {
       TmpNhom::create([
            'ten_sinh_vien'=>$request->ten_sinh_vien,
            'id_sinh_vien'=>$request->id,
            'diem_gpa'=>$request->diem_gpa,
       ]);

       $sinh_vien = SinhVien::find($request->id);
       $sinh_vien->tinh_trang = 1;
       $sinh_vien->save();

        return response()->json([
            'status'=> 1,
            'message'=>'Thành Công'
        ]);
    }

    public function deleteData(Request $request)
    {
        $tmpnhom = TmpNhom::where('id', $request->id)->first();

        if ($tmpnhom) {
            $tmpnhom->delete();

            $sinh_vien = SinhVien::find($request->id_sinh_vien);
            $sinh_vien->tinh_trang = 0;
            $sinh_vien->save();

            return response()->json([
                'status'        => 1,
                'message'       => "Đã xóa thành công",
            ]);
        } else {
            return response()->json([
                'status'        => 0,
                'message'       => "Không tìm thấy ",
            ]);
        }
    }
}
