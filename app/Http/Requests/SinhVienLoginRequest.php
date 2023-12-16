<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SinhVienLoginRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'ma_sinh_vien'  => "required|numeric",
            'password'      => "required",
        ];
    }

    public function messages()
    {
        return [
            'ma_sinh_vien.numeric'      => "Mã số sinh viên không phải số!",
            'ma_sinh_vien.required'     => "Vui lòng nhập mã sinh viên!",
            'password.required'         => "Vui lòng nhập mật khẩu!",
        ];
    }
}
