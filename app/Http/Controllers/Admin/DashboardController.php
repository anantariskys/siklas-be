<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RiwayatKlasifikasi;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        // Total klasifikasi seluruh user
        $totalKlasifikasi = RiwayatKlasifikasi::count();

        // Total user
        $totalUser = User::where('role', 'user')->count();

        // Topik / Bidang paling banyak muncul
        $bidangTerbanyak = RiwayatKlasifikasi::select('prediksi_topik', DB::raw('COUNT(*) as total'))
            ->groupBy('prediksi_topik')
            ->orderByDesc('total')
            ->first();

        // Rata-rata akurasi seluruh klasifikasi
        $rataAkurasi = round(RiwayatKlasifikasi::avg('confidence_score'), 2);

        // Klasifikasi bulan ini
        $klasifikasiBulanIni = RiwayatKlasifikasi::whereMonth('diklasifikasi_pada', now()->month)
            ->whereYear('diklasifikasi_pada', now()->year)
            ->count();

        // Persebaran bidang penelitian
        $persebaranBidang = RiwayatKlasifikasi::select('prediksi_topik', DB::raw('COUNT(*) as total'))
            ->groupBy('prediksi_topik')
            ->orderByDesc('total')
            ->get();

        // Tren per bulan (12 bulan terakhir)
        $trenKlasifikasi = RiwayatKlasifikasi::select(
            DB::raw('MONTH(diklasifikasi_pada) as bulan'),
            DB::raw('COUNT(*) as total')
        )
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

        // Akurasi per bidang
        $akurasiPerBidang = RiwayatKlasifikasi::select(
            'prediksi_topik',
            DB::raw('ROUND(AVG(confidence_score), 2) as rata_akurasi')
        )
            ->groupBy('prediksi_topik')
            ->orderByDesc('rata_akurasi')
            ->get();

        // Distribusi confidence (global)
        $distribusiConfidence = [
            'rendah' => RiwayatKlasifikasi::where('confidence_score', '<', 60)->count(),
            'sedang' => RiwayatKlasifikasi::whereBetween('confidence_score', [60, 80])->count(),
            'tinggi' => RiwayatKlasifikasi::where('confidence_score', '>', 80)->count(),
        ];

        // Riwayat terbaru (10 data)
        $riwayatTerbaru = RiwayatKlasifikasi::orderByDesc('diklasifikasi_pada')
            ->take(10)
            ->get(['user_id', 'judul', 'prediksi_topik', 'confidence_score', 'diklasifikasi_pada']);

        return $this->successResponse([
            'total_klasifikasi' => $totalKlasifikasi,
            'total_user' => $totalUser,
            'bidang_terbanyak' => $bidangTerbanyak?->prediksi_topik,
            'klasifikasi_bulan_ini' => $klasifikasiBulanIni,
            'rata_akurasi' => $rataAkurasi,
            'persebaran_bidang' => $persebaranBidang,
            'tren_klasifikasi' => $trenKlasifikasi,
            'akurasi_per_bidang' => $akurasiPerBidang,
            'distribusi_confidence' => $distribusiConfidence,
            'riwayat_terbaru' => $riwayatTerbaru,
        ], 'Data dashboard admin berhasil diambil');
    }
}
