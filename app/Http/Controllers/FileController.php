<?php

namespace App\Http\Controllers;

use App\Models\KeHoach;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class FileController extends Controller
{
    public function uploadFile(Request $request)
    {
        try {
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('public/uploaded_files', $fileName);
                KeHoach::create([
                    'ten_ke_hoach'  => $request->ten_ke_hoach,
                    'ngay'          => $request->ngay,
                    'mo_ta'         => $request->mo_ta,
                    'file'          => $request->file,
                    'tinh_trang'    => $request->tinh_trang,
                    'file'          => $fileName,
                ]);
                return response()->json(['message' => 'Đã thêm mới thành công!', 'status' => 1]);
            } else {
                return response()->json(['error' => 'No file uploaded.'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'File upload failed.'], 500);
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

    public function statusKeHoach(Request $request)
    {
        $data = KeHoach::find($request->id);
        if($data) {
            $data->tinh_trang = !$data->tinh_trang;
            $data->save();
            return response()->json([
                'status'    => 1,
                'message'   => 'Đã đổi trạng thái thành công!',
            ]);
        } else {
            return response()->json([
                'status'    => 1,
                'message'   => 'Kế hoạch không tồn tại trong hệ thống!',
            ]);
        }
    }
    public function delete_plan(Request $request)
    {
        $KeHoach = KeHoach::find($request->id);
        if ($KeHoach) {
            $KeHoach->delete();

            return response()->json([
                'status'    => 1,
                'message'   => 'Đã xóa kế hoạch thành công ' ,
            ]);
        } else {
            return response()->json([
                'status'    => 0,
                'message'   => 'Kế hoạch không tồn tại!',
            ]);
        }
    }
    public function updateFile(Request $request)
    {
        $ke_hoach = KeHoach::find($request->id);
        if ($ke_hoach) {
            if($request->file != null) {

            } else {

            }
        } else {
            # code...
        }

        // try {
        //     if ($request->hasFile('file')) {
        //         $file = $request->file('file');
        //         $fileName = time() . '_' . $file->getClientOriginalName();
        //         $file->storeAs('public/uploaded_files', $fileName);
        //         KeHoach::updated([
        //             'ten_ke_hoach'  => $request->ten_ke_hoach,
        //             'ngay'          => $request->ngay,
        //             'mo_ta'         => $request->mo_ta,
        //             'file'          => $request->file,
        //             'tinh_trang'    => $request->tinh_trang,
        //             'file'          => $fileName,
        //         ]);
        //         return response()->json(['message' => 'Đã cập nhật thành công!', 'status' => 1]);
        //     } else {
        //         return response()->json(['error' => 'No file uploaded.'], 400);
        //     }
        // } catch (\Exception $e) {
        //     return response()->json(['error' => 'File upload failed.'], 500);
        // }
    }
}
