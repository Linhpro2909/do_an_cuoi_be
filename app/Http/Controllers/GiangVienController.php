<?php

namespace App\Http\Controllers;

use App\Models\GiangVien;
use App\Models\HoiDong;
use App\Models\NhatKyNhom;
use App\Models\Nhom;
use App\Models\SinhVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GiangVienController extends Controller
{
    public function actionApiLoginGiangVien(Request $request)
    {
        $data['email']             = $request->email;
        $data['password']          = $request->password;
        $check = Auth::guard('giang_vien')->attempt($data);
        if ($check) {
            $user = Auth::guard('giang_vien')->user();
            $tokenResult = $user->createToken('authToken')->plainTextToken;
            return response()->json([
                'status'    => true,
                'message'   => 'Đăng Nhập Thành Công!',
                'token'     => $tokenResult,
                'token_type' => 'Bearer',
            ]);
        }
        return response()->json([
            'status'    => false,
            'message'   => 'Tài khoản hoặc mật khẩu không đúng!',
        ]);
    }

    public function Logout()
    {
        Auth::guard('giang_vien')->logout();
        return response()->json([
            'status'    => 1,
            'message'   => "Đã đăng xuất thành công!"
        ]);
    }

    public function createData(Request $request)
    {
        $data = $request->all();
        $data['password'] = bcrypt($data['password']);
        GiangVien::create($data);
        return response()->json([
            'status'        => 1,
            'message'       => "Đã thêm giảng viên thành công!",
            'data'       => $request->all(),
        ]);
    }
    public function getData()
    {
        $data = GiangVien::get();
        return response()->json([
            'status'    => 1,
            'data'      => $data,
        ]);
    }
    public function updateData(Request $request)
    {
        $sinh_vien  = GiangVien::where('id', $request->id)->first();
        if ($sinh_vien) {
            $sinh_vien->update($request->all());
            return response()->json([
                'status'    => 1,
                'message'   => 'Đã cập nhật thành công!',

            ]);
        } else {
            return response()->json([
                'status'    => 0,
                'message'   => 'Giảng viên không tồn tại',
            ]);
        }
    }
    public function deleteData(Request $request)
    {

        $data = $request->all();

        $str = "";

        foreach ($data as $key => $value) {
            if (isset($value['check'])) {
                $str .= $value['id'] . ",";
            }

            $data_id = explode(",", rtrim($str, ","));

            foreach ($data_id as $k => $v) {
                $sinh_vien = GiangVien::where('id', $v);

                if ($sinh_vien) {
                    $sinh_vien->delete();
                } else {
                    return response()->json([
                        'status'    => false,
                        'message'   => 'Đã có lỗi sự cố!',
                    ]);
                }
            }
        }

        return response()->json([
            'status'    => true,
            'message'   => 'Đã xóa thành công!',
        ]);
    }
    public function searchData(Request $request)
    {
        $ten_can_tim    = '%' . $request->ten_giang_vien . '%';
        $data   = GiangVien::where('ten_giang_vien', 'like', $ten_can_tim)->get();

        return response()->json([
            'data'          => $data,
        ]);
    }

    public function getNhomDoAn(Request $request) {
        $data = Nhom::where('nhoms.id_giang_vien', $request->id)
                    ->join('giang_viens', 'giang_viens.id', 'nhoms.id_giang_vien')
                    ->leftJoin('de_tai_sinh_viens', function ($join) {
                        $join->on('de_tai_sinh_viens.ma_nhom', '=', 'nhoms.ma_nhom')
                             ->where(function ($query) {
                                 $query->where('de_tai_sinh_viens.tinh_trang', 1)
                                       ->orWhere('de_tai_sinh_viens.tinh_trang', 0)
                                       ->orWhere('de_tai_sinh_viens.tinh_trang', 2);
                             });
                    })
                    ->leftJoin('hoi_dongs', 'hoi_dongs.id', 'nhoms.id_hoi_dong')
                    ->select('nhoms.ten_nhom', 'nhoms.ma_nhom', 'nhoms.id_giang_vien', 'giang_viens.ten_giang_vien', 'de_tai_sinh_viens.ten_de_tai', 'hoi_dongs.ten_hoi_dong', 'de_tai_sinh_viens.tinh_trang')
                    ->groupBy('nhoms.ten_nhom', 'nhoms.ma_nhom', 'nhoms.id_giang_vien', 'giang_viens.ten_giang_vien', 'de_tai_sinh_viens.ten_de_tai', 'hoi_dongs.ten_hoi_dong', 'de_tai_sinh_viens.tinh_trang')
                    ->get();
        foreach ($data as $key => $value) {
            $list_tv = Nhom::where('nhoms.ma_nhom', $value['ma_nhom'])
                            ->join('sinh_viens', 'sinh_viens.id', 'nhoms.id_sinh_vien')
                            ->select('nhoms.id_sinh_vien', 'sinh_viens.ten_sinh_vien','sinh_viens.diem_mentor')
                            ->get();
            $value['list'] = $list_tv;
        }

        return response()->json([
            'data'   => $data,
        ]);
    }

    public function updateDiemMentor(Request $request)
    {
        $data = $request->all();
        foreach ($data['list'] as $key => $value) {
            $sinh_vien = SinhVien::where('id', $value['id_sinh_vien'])->first();
            $sinh_vien->diem_mentor = $value['diem_mentor'];
            $sinh_vien->save();
        }

        return response()->json([
            'status'    => 1,
            'message'   => 'Đã cho điểm thành công!',
        ]);
    }

    public function getDataChiTiet(Request $request)
    {
        $data      = NhatKyNhom::where('ma_nhom', $request->ma_nhom)
                                ->select('nhat_ky_nhoms.*', DB::raw ("DATE_FORMAT(thoi_gian,'%d/%m/%Y') as thoi_gian "))->get();
        return response()->json([
            'status'    => 1,
            'data'      => $data,
        ]);

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

    public function getNhom(Request $request)
    {
        $hoiDong = HoiDong::whereRaw("FIND_IN_SET(?, list_id_hoi_dong)", [$request->id])
                                 ->get();
        foreach($hoiDong as $key => $value) {
            $arr = explode(',', $value['list_ma_nhom']);
            $idsArray = explode(',', $value['list_id_hoi_dong']);
            $positions = array_keys($idsArray, (string)$request->id); // Tìm tất cả các vị trí của số 2

            // Vì mảng bắt đầu từ 0, nên bạn cần cộng thêm 1 để có thứ tự thực tế
            $positions = array_map(function($pos) { return $pos; }, $positions);
            $value['vi_tri'] = $positions[0];
            foreach ($arr as $k => $v) {
                $data = Nhom::where('nhoms.ma_nhom', $v)
                                ->join('giang_viens', 'giang_viens.id', 'nhoms.id_giang_vien')
                                ->join('de_tai_sinh_viens', 'de_tai_sinh_viens.ma_nhom', 'nhoms.ma_nhom')
                                ->join('hoi_dongs', 'hoi_dongs.id', 'nhoms.id_hoi_dong')
                                ->select('nhoms.ten_nhom', 'nhoms.ma_nhom', 'nhoms.id_giang_vien', 'giang_viens.ten_giang_vien', 'de_tai_sinh_viens.ten_de_tai', 'hoi_dongs.ten_hoi_dong')
                                ->groupBy('nhoms.ten_nhom', 'nhoms.ma_nhom', 'nhoms.id_giang_vien', 'giang_viens.ten_giang_vien', 'de_tai_sinh_viens.ten_de_tai', 'hoi_dongs.ten_hoi_dong')
                                ->get();
                foreach ($data as $k_1 => $v_1) {
                    $list_tv = Nhom::where('nhoms.ma_nhom', $v_1['ma_nhom'])
                                    ->join('sinh_viens', 'sinh_viens.id', 'nhoms.id_sinh_vien')
                                    ->select('nhoms.id_sinh_vien', 'sinh_viens.ten_sinh_vien', 'sinh_viens.diem_chu_tich', 'sinh_viens.diem_thu_ky', 'sinh_viens.diem_uy_vien')
                                    ->get();
                    foreach ($list_tv as $k_2 => $v_2) {
                        $diem_tong = ((($v_2['diem_chu_tich'] + $v_2['diem_thu_ky'] + $v_2['diem_uy_vien']) / 3) * 0.7);
                        $v_2['diem_tong'] = round($diem_tong, 2);
                    }
                    $value['ma_nhom'] = $v_1['ma_nhom'];
                    $value['ten_nhom'] = $v_1['ten_nhom'];
                    $value['ten_giang_vien'] = $v_1['ten_giang_vien'];
                    $value['id_giang_vien'] = $v_1['id_giang_vien'];
                    $value['ten_de_tai'] = $v_1['ten_de_tai'];
                    $value['ten_hoi_dong'] = $v_1['ten_hoi_dong'];
                    $value['list'] = $list_tv;

                }
            }
        }

        return response()->json([
            'data'   => $hoiDong,
        ]);
    }

    public function updateDiem(Request $request)
    {
        $data = $request->all();
        foreach ($data['list'] as $key => $value) {
            $sinh_vien = SinhVien::where('id', $value['id_sinh_vien'])->first();
            if($request['vi_tri'] == 0) {
                $sinh_vien->diem_chu_tich = $value['diem_chu_tich'];
            } elseif($request['vi_tri'] == 1) {
                $sinh_vien->diem_thu_ky = $value['diem_thu_ky'];
            } else {
                $sinh_vien->diem_uy_vien = $value['diem_uy_vien'];
            }
            $sinh_vien->save();
        }

        return response()->json([
            'status'    => 1,
            'message'   => 'Đã cho điểm thành công!',
        ]);
    }
}
