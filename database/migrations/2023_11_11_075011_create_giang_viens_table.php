<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('giang_viens', function (Blueprint $table) {
            $table->id();
            $table->string('ma_giang_vien');
            $table->string('ten_giang_vien');
            $table->date('ngay_thang_nam_sinh');
            $table->string('dia_chi');
            $table->string('dia_chi_cong_tac');
            $table->string('email');
            $table->string('password');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('giang_viens');
    }
};
