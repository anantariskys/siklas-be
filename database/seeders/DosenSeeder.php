<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Dosen;
use App\Models\BidangPenelitian;

class DosenSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan tabel bidang_penelitians sudah ada datanya
        $bidangPenelitians = BidangPenelitian::all();

        if ($bidangPenelitians->isEmpty()) {
            $this->command->warn('⚠️  Tidak ada data bidang penelitian. Jalankan BidangPenelitianSeeder terlebih dahulu.');
            return;
        }

        // Daftar nama depan dan belakang untuk membuat variasi nama
        $namaDepan = [
            'Dr.', 'Prof.', 'Ir.', 'Drs.', 'M.', 'Dr. Eng.', 'Prof. Dr.'
        ];

        $namaOrang = [
            'Budi Santoso', 'Rina Kartika', 'Ahmad Fauzi', 'Maya Sari', 'Dimas Prasetyo',
            'Nurul Hidayah', 'Andi Wijaya', 'Reni Wulandari', 'Taufik Rahman', 'Sinta Lestari',
            'Agus Setiawan', 'Fajar Prakoso', 'Indah Puspita', 'Yusuf Maulana', 'Dewi Sartika',
            'Bayu Kurniawan', 'Lina Marlina', 'Hendra Saputra', 'Fitriani Rahmah', 'Rizky Nugroho',
            'Nadia Salsabila', 'Dedi Irawan', 'Eka Putri', 'Joko Susilo', 'Sarah Amelia',
            'Reza Firmansyah', 'Ratna Dewi', 'Rio Pratama', 'Ayu Lestari', 'Hana Setyaningsih',
            'Toni Gunawan', 'Wahyu Ramadhan', 'Anisa Putri', 'Iwan Kurnia', 'Citra Dewi',
            'Rendy Pradana', 'Mellya Safitri', 'Bagus Santosa', 'Vina Maharani', 'Rafi Fauzan',
            'Silvi Oktaviani', 'Gilang Saputra', 'Farhan Hidayat', 'Laila Fitri', 'Adi Permana',
            'Putri Anggraini', 'Dion Firmansyah', 'Mega Rahmawati', 'Bambang Sutrisno', 'Aulia Sari',
            'Yoga Pratama', 'Sari Melati', 'Hafiz Ramadhan', 'Desi Nuraini', 'Andre Kurniawan',
            'Intan Permata', 'Kurnia Ramdani', 'Selvi Oktavia', 'Ridho Fadhil', 'Tia Amalia',
            'Evan Santoso', 'Lutfi Maulana', 'Rosa Anggraini', 'Zaki Pratomo', 'Diana Fitriani',
            'Yuni Astuti', 'Ferry Saputra', 'Kiki Lestari', 'Angga Permadi', 'Mila Rahmi',
            'Sofyan Hadi', 'Wulan Sari', 'Rahmat Hidayat', 'Susi Wulandari', 'Teguh Santoso',
            'Nita Amelia', 'Hani Fitriyah', 'Bagas Prasetyo', 'Della Rahma', 'Agung Widodo',
            'Aditia Nugraha', 'Salsa Amelia', 'Andra Putra', 'Rara Salsabila', 'Naufal Hakim',
            'Evi Susanti', 'Yuli Pratiwi', 'Galih Ramadhan', 'Mira Safitri', 'Ari Wibowo',
            'Anita Pramesti', 'Herman Setyo', 'Cindy Lestari', 'Alfi Rahman', 'Bella Putri',
            'Nanda Aprilia', 'Rio Setiawan', 'Rika Amelia', 'Rafli Gunawan', 'Yasmin Zahra'
        ];

        // Membuat 100 dosen secara acak
        for ($i = 0; $i < 100; $i++) {
            $prefix = $namaDepan[array_rand($namaDepan)];
            $nama = $prefix . ' ' . $namaOrang[array_rand($namaOrang)];

            $major = $bidangPenelitians->random();
            $minor = rand(0, 1) ? $bidangPenelitians->random() : null; // kadang ada minor

            Dosen::create([
                'id' => Str::uuid(),
                'nama' => $nama,
                'bidang_penelitian_major_id' => $major->id,
                'bidang_penelitian_minor_id' => $minor?->id,
            ]);
        }

        $this->command->info('✅ 100 data dosen berhasil dibuat oleh DosenSeeder.');
    }
}
