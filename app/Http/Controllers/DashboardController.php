<?php

namespace App\Http\Controllers;

use App\Models\RiwayatKlasifikasi;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $userId = $request->query('user_id');

        if (!$userId) {
            return $this->errorResponse('Tidak ada query parameter user_id', 'User ID tidak ditemukan', 400);
        }

        // Total klasifikasi milik user
        $totalKlasifikasi = RiwayatKlasifikasi::where('user_id', $userId)->count();

        // Bidang/topik terbanyak
        $bidangTerbanyak = RiwayatKlasifikasi::select('prediksi_topik', DB::raw('COUNT(*) as total'))
            ->where('user_id', $userId)
            ->groupBy('prediksi_topik')
            ->orderByDesc('total')
            ->first();

        // Klasifikasi bulan ini
        $klasifikasiBulanIni = RiwayatKlasifikasi::where('user_id', $userId)
            ->whereMonth('diklasifikasi_pada', now()->month)
            ->whereYear('diklasifikasi_pada', now()->year)
            ->count();

        // Rata-rata akurasi
        $rataAkurasi = round(RiwayatKlasifikasi::where('user_id', $userId)->avg('confidence_score'), 2);

        // Persebaran bidang penelitian
        $persebaranBidang = RiwayatKlasifikasi::select('prediksi_topik', DB::raw('COUNT(*) as total'))
            ->where('user_id', $userId)
            ->groupBy('prediksi_topik')
            ->orderByDesc('total')
            ->get();

        // 🔹 Tren Klasifikasi per Bulan (12 bulan terakhir)
        $trenKlasifikasi = RiwayatKlasifikasi::select(
            DB::raw('MONTH(diklasifikasi_pada) as bulan'),
            DB::raw('COUNT(*) as total')
        )
            ->where('user_id', $userId)
            ->whereYear('diklasifikasi_pada', now()->year)
            ->groupBy(DB::raw('MONTH(diklasifikasi_pada)'))
            ->orderBy(DB::raw('MONTH(diklasifikasi_pada)'))
            ->get()
            ->map(function ($item) {
                return [
                    'bulan' => now()->setMonth($item->bulan)->translatedFormat('F'),
                    'total' => $item->total,
                ];
            });

        // 🔹 Akurasi per Bidang
        $akurasiPerBidang = RiwayatKlasifikasi::select(
            'prediksi_topik',
            DB::raw('ROUND(AVG(confidence_score), 2) as rata_akurasi')
        )
            ->where('user_id', $userId)
            ->groupBy('prediksi_topik')
            ->orderByDesc('rata_akurasi')
            ->get();

        // 🔹 Distribusi Confidence Score (kategori)
        $distribusiConfidence = [
            'rendah' => RiwayatKlasifikasi::where('user_id', $userId)
                ->where('confidence_score', '<', 60)
                ->count(),
            'sedang' => RiwayatKlasifikasi::where('user_id', $userId)
                ->whereBetween('confidence_score', [60, 80])
                ->count(),
            'tinggi' => RiwayatKlasifikasi::where('user_id', $userId)
                ->where('confidence_score', '>', 80)
                ->count(),
        ];

        // 🔹 Riwayat Klasifikasi Terakhir (5 data)
        $riwayatTerakhir = RiwayatKlasifikasi::where('user_id', $userId)
            ->orderByDesc('diklasifikasi_pada')
            ->take(5)
            ->get(['judul', 'abstrak', 'prediksi_topik', 'confidence_score', 'diklasifikasi_pada']);

        return $this->successResponse([
            'user_id' => $userId,
            'total_klasifikasi' => $totalKlasifikasi,
            'bidang_terbanyak' => $bidangTerbanyak?->prediksi_topik,
            'klasifikasi_bulan_ini' => $klasifikasiBulanIni,
            'rata_akurasi' => $rataAkurasi,
            'persebaran_bidang' => $persebaranBidang,
            'tren_klasifikasi' => $trenKlasifikasi,
            'akurasi_per_bidang' => $akurasiPerBidang,
            'distribusi_confidence' => $distribusiConfidence,
            'riwayat_terakhir' => $riwayatTerakhir,
        ], 'Data dashboard berhasil diambil', 200);
    }
}
