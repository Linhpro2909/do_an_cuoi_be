<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nhat_ky_nhoms', function (Blueprint $table) {
            $table->id();
            $table->string('ten_nhat_ky');
            $table->date('thoi_gian');
            $table->string('mo_ta');
            $table->integer('tinh_trang');
            $table->string('file')->nullable();
            $table->string('ten_file')->nullable();
            $table->string('ma_nhom');
            $table->integer('id_sinh_vien');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nhat_ky_nhoms');
    }
};
