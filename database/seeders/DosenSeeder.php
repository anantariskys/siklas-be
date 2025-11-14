<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\BidangPenelitian;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DosenSeeder extends Seeder
{
    public function run(): void
    {
        // Daftar gelar awal & akhir
        $gelarAwalList = ['Dr.', 'Prof.', 'Ir.', 'Drs.', ''];
        $gelarAkhirList = ['S.T.', 'M.T.', 'M.Kom.', 'M.Sc.', 'Ph.D.', 'M.Eng.', 'S.Kom.', 'Dr.Eng.'];

        // Ambil semua bidang penelitian yang tersedia
        $bidangs = BidangPenelitian::all();

        // Daftar nama dosen contoh
        $namaList = [
            'Budi Santoso',
            'Rina Wulandari',
            'Andi Prasetyo',
            'Dewi Lestari',
            'Rahmat Hidayat',
            'Siti Aminah',
            'Dimas Nugroho',
            'Lina Marlina',
            'Bagus Saputra',
            'Anita Sari',
        ];

        foreach ($namaList as $nama) {
            // Pilih gelar secara acak
            $gelarAwal = $gelarAwalList[array_rand($gelarAwalList)];
            $gelarAkhir = $gelarAkhirList[array_rand($gelarAkhirList)];

            // Pilih bidang major & minor acak
            $major = $bidangs->random();
            $minor = $bidangs->where('id', '!=', $major->id)->random();

            Dosen::create([
                'id' => Str::uuid(),
                'nama' => $nama,
                'gelar_awal' => $gelarAwal,
                'gelar_akhir' => $gelarAkhir,
                'bidang_penelitian_major_id' => $major->id,
                'bidang_penelitian_minor_id' => $minor->id,
            ]);
        }
    }
}
