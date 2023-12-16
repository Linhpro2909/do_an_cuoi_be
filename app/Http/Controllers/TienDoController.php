<?php

namespace App\Http\Controllers;

use App\Models\NhatKiNhom;
use App\Models\NhatKyNhom;
use App\Models\Nhom;
use App\Models\TienDo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TienDoController extends Controller
{
    public function getDataTienDo()
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
            'data'   => $data,
        ]);
    }
    public function chiTietNhom(Request $request)
    {
        $nhat_ky_nhom = NhatKyNhom::where('ma_nhom', $request->ma_nhom)
                                    ->select('nhat_ky_nhoms.*', DB::raw ("DATE_FORMAT(thoi_gian,'%d/%m/%Y') as thoi_gian "))->get();
        return response()->json([
            'data'   => $nhat_ky_nhom,
        ]);
    }
}
