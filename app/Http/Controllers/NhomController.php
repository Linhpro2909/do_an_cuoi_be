<?php

namespace App\Http\Controllers;

use App\Models\Nhom;
use App\Models\SinhVien;
use App\Models\TmpNhom;
use Illuminate\Http\Request;

class NhomController extends Controller
{

    public function createData(Request $request)
    {
        foreach ($request->list as $key => $value) {
            Nhom::create([
                'ten_nhom'         =>  $request->ten_nhom,
                'ma_nhom'          =>  $request->ma_nhom,
                'id_sinh_vien'     =>  $value['id_sinh_vien'],
                'id_giang_vien'    =>  $request->id_giang_vien,
            ]);

            $tmp_nhom = TmpNhom::find($value['id'])->delete();
        }
        return response()->json([
            'status' => 1,
            'message' => "Oke"
        ]);
    }

    public function getDataNhom()
    {

        $data = Nhom::join('giang_viens', 'giang_viens.id', 'nhoms.id_giang_vien')
                    ->select('nhoms.ten_nhom', 'nhoms.ma_nhom', 'nhoms.id_giang_vien', 'giang_viens.ten_giang_vien')
                    ->groupBy('nhoms.ten_nhom', 'nhoms.ma_nhom', 'nhoms.id_giang_vien', 'giang_viens.ten_giang_vien')
                    ->get();
        foreach ($data as $key => $value) {
            $list_tv = Nhom::where('nhoms.ma_nhom', $value['ma_nhom'])
                            ->join('sinh_viens', 'sinh_viens.id', 'nhoms.id_sinh_vien')
                            ->select('nhoms.id_sinh_vien', 'sinh_viens.ten_sinh_vien')
                            ->get();
            $value['list'] = $list_tv;
        }
        return response()->json([
            'data' => $data,
        ]);
    }

    public function getSinhVienNhom()
    {
        $ket_qua = SinhVien::leftJoin('nhoms', 'sinh_viens.id', '=', 'nhoms.id_sinh_vien')
                    ->whereNull('nhoms.id_sinh_vien')
                    ->select('sinh_viens.*')
                    ->get();
        return response()->json([
            'data' => $ket_qua,
        ]);
    }

    public function thayDoiSinhVienNhom(Request $request)
    {
        $sinh_vien_doi = SinhVien::find($request->id_sinh_vien_doi);
        $nhom          = Nhom::where('id_sinh_vien', $request->id_sinh_vien_doi)->first();
        if ($sinh_vien_doi && $nhom) {
            $nhom->id_sinh_vien = $request->id_sinh_moi;
            $nhom->save();
            $sinh_vien_moi = SinhVien::find($request->id_sinh_moi);
            $sinh_vien_moi->tinh_trang = 1;
            $sinh_vien_moi->save();
            $sinh_vien_doi->tinh_trang = 0;
            $sinh_vien_doi->save();

            $ket_qua = SinhVien::leftJoin('nhoms', 'sinh_viens.id', '=', 'nhoms.id_sinh_vien')
                                ->whereNull('nhoms.id_sinh_vien')
                                ->select('sinh_viens.*')
                                ->get();

            return response()->json([
                'status'    => 1,
                'data'      => $ket_qua,
                'message'   => "Đã thay đổi thành viên nhóm thành công!"
            ]);
        } else {
            return response()->json([
                'status'    => 0,
                'message'   => "Đã gặp lỗi gì đó!"
            ]);
        }




    }
    public function deleteData(Request $request)
    {
        $ma_nhom =  Nhom::where('ma_nhom', $request->ma_nhom)->first();
        if ($ma_nhom) {
            $data   = Nhom::where('ma_nhom', $request->ma_nhom)->get();
            foreach($data as $key => $value) {
                $sinh_vien = SinhVien::where('id', $value['id_sinh_vien'])->first();
                $nhom = Nhom::where('id_sinh_vien', $sinh_vien->id)->first();
                $sinh_vien->tinh_trang = 0;
                $sinh_vien->save();
                $nhom->delete();
            }

            return response()->json([
                'status'    => 1,
                'message'   => 'Đã xóa thành công!',
            ]);
        } else {
            return response()->json([
                'status'    => 0,
                'message'   => 'Lỗi!',
            ]);
        }
    }
}
