<?php

namespace Database\Seeders;

use App\Models\GiangVien;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        $this->call(AdminSeeder::class);
        $this->call(SinhVienSeeder::class);
        $this->call(GiangVienSeeder::class);
        $this->call(KhoaSeeder::class);
    }
}
