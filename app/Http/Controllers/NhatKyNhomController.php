<?php

namespace App\Http\Controllers;

use App\Models\DeTaiSinhVien;
use App\Models\KeHoach;
use App\Models\NhatKyNhom;
use App\Models\Nhom;
use App\Models\SinhVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NhatKyNhomController extends Controller
{
    public function createNhatKy(Request $request)
    {
        $sinh_vien = SinhVien::where('id', $request->id_user)->where('sinh_viens.tinh_trang', 1)->first();
        if ($sinh_vien) {
            $data = $request->all();
            $nhom = SinhVien::where('sinh_viens.id', $request->id_user)->join('nhoms', 'nhoms.id_sinh_vien', 'sinh_viens.id')->first();
            $data['ma_nhom']      = $nhom->ma_nhom;
            $data['id_sinh_vien'] = $request->id_user;
            NhatKyNhom::create($data);
            return response()->json([
                'status'    => 1,
                'message'   => 'Đã thêm mới thành công' . $request->id_user,
            ]);
        } else {
            return response()->json([
                'status'    => 0,
                'message'   => 'Sinh Viên chưa được vào nhóm' . $request->id_user,
            ]);
        }


    }

    public function getData(Request $request)
    {
        $sinh_vien = NhatKyNhom::where('id_sinh_vien', $request->id_user)->first();
        if ($sinh_vien != null) {
            $data      = NhatKyNhom::where('ma_nhom', $sinh_vien->ma_nhom)
                                    ->select('nhat_ky_nhoms.*', DB::raw ("DATE_FORMAT(thoi_gian,'%d/%m/%Y') as thoi_gian "))->get();
        } else {
            $data = [];
        }

        return response()->json([
            'status'    => 1,
            'data'      => $data,
        ]);

    }

    public function getNhomDoAn(Request $request)
    {
        $ma = SinhVien::where('sinh_viens.id', $request->id_user)
                    ->join('nhoms', 'nhoms.id_sinh_vien', 'sinh_viens.id')
                    ->first();

        $data = Nhom::where('nhoms.ma_nhom', $ma->ma_nhom)
                    ->leftJoin('de_tai_sinh_viens', function ($join) {
                        $join->on('de_tai_sinh_viens.ma_nhom', '=', 'nhoms.ma_nhom')
                             ->where(function ($query) {
                                 $query->where('de_tai_sinh_viens.tinh_trang', 1)
                                       ->orWhere('de_tai_sinh_viens.tinh_trang', 0)
                                       ->orWhere('de_tai_sinh_viens.tinh_trang', 2);
                             });
                    })
                    ->join('giang_viens', 'giang_viens.id', 'nhoms.id_giang_vien')
                    ->leftJoin('hoi_dongs', 'hoi_dongs.id', 'nhoms.id_hoi_dong')
                    ->select('nhoms.ten_nhom', 'nhoms.ma_nhom', 'nhoms.id_giang_vien', 'giang_viens.ten_giang_vien', 'de_tai_sinh_viens.ten_de_tai', 'hoi_dongs.ten_hoi_dong', 'de_tai_sinh_viens.tinh_trang')
                    ->groupBy('nhoms.ten_nhom', 'nhoms.ma_nhom', 'nhoms.id_giang_vien', 'giang_viens.ten_giang_vien', 'de_tai_sinh_viens.ten_de_tai', 'hoi_dongs.ten_hoi_dong', 'de_tai_sinh_viens.tinh_trang')
                    ->get();


        foreach ($data as $key => $value) {
            $list_tv = Nhom::where('nhoms.ma_nhom', $value['ma_nhom'])
                            ->join('sinh_viens', 'sinh_viens.id', 'nhoms.id_sinh_vien')
                            ->select('nhoms.id_sinh_vien', 'sinh_viens.ten_sinh_vien','sinh_viens.diem_mentor', 'sinh_viens.diem_chu_tich', 'sinh_viens.diem_thu_ky', 'sinh_viens.diem_uy_vien')
                            ->get();
            foreach ($list_tv as $k_1 => $v_1) {
                $diem_tong = ($v_1['diem_mentor'] * 0.3) + ((($v_1['diem_chu_tich'] + $v_1['diem_thu_ky'] + $v_1['diem_uy_vien']) / 3) * 0.7);
                $v_1['diem_tong'] = round($diem_tong, 2);
            }

            $value['list'] = $list_tv;
        }

        // $data->ten_de_tai = $de_tai->ten_de_tai;

        return response()->json([
            'data' => $data,
        ]);
    }

    public function uploadFile(Request $request)
    {

        try {

            if ($request->hasFile('file')) {
                $ke_hoach = NhatKyNhom::find($request->id);
                $file = $request->file('file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('public/uploaded_files', $fileName);
                if ($ke_hoach) {
                    $ke_hoach->update([
                        'ten_file'      => $request->ten_file,
                        'file'          => $fileName
                    ]);
                    return response()->json(['ke_hoach' => $ke_hoach, 'message' => 'Đã upload file thành công!', 'status' => 1]);

                }
            } else {
                return response()->json(['error' => 'No file uploaded.'], 400);
            }
        } catch (\Exception $e) {
            return response()->json([$e,'error' => 'File upload failed.'], 500);
        }
    }

    public function downloadFile($filename)
    {
        $filePath = storage_path("app/public/uploaded_files/{$filename}");
        if (file_exists($filePath)) {
            $name = basename($filePath);

            return response()->download($filePath, $name);
        } else {
            return response()->json(['error' => 'File not found.'], 404);
        }
    }

    public function deleteNhatKy(Request $request)
    {
        $ke_hoach = NhatKyNhom::find($request->id);
        if ($ke_hoach) {
            $ke_hoach->delete();
            return response()->json([
                'status'    => 1,
                'message'   => 'Đã xóa thành công nhật ký!',
            ]);
        } else {
            return response()->json([
                'status'    => 0,
                'message'   => 'Nhật ký không tồn tại!!',
            ]);
        }
    }

}
