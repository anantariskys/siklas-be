<?php

namespace Database\Seeders;

use App\Models\BidangPenelitian;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class BidangPenelitanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bidang = [
            'Komputasi Berbasis Jaringan',
            'Komputasi Cerdas',
            'Multimedia, Game dan Mobile',
            'Pengembangan Sistem Informasi',
            'Rekayasa Sistem Komputer',
            'Jaringan Komputer',
            'Rekayasa Perangkat Cerdas',
            'Robotika',
            'Rekayasa Perangkat Lunak',
            'Sistem Informasi Geografis',
            'Manajemen Data & Informasi',
            'Sistem Cerdas',
            'Tata Kelola & Manajemen Sistem Informasi',
            'Arsitektur & Organisasi Komputer',
            'Ilmu Kependidikan dan Teknologi Pembelajaran',
            'Integrasi Teknologi Informasi',
        ];

        foreach ($bidang as $nama) {
           BidangPenelitian::create([
                'id' => Str::uuid(),
                'nama' => $nama,
                'slug' => Str::slug($nama),
                'created_at' => now(),
                'updated_at' => now(),
           ]);
        }
    }
}
