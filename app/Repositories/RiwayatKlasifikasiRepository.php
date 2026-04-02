<?php

namespace App\Repositories;

use App\Models\RiwayatKlasifikasi;
use App\Repositories\Interfaces\RiwayatKlasifikasiRepositoryInterface;

class RiwayatKlasifikasiRepository extends BaseRepository implements RiwayatKlasifikasiRepositoryInterface
{
    public function __construct(RiwayatKlasifikasi $model)
    {
        parent::__construct($model);
    }

    private function applyRoleFilters($query, \App\Models\User $user)
    {
        if ($user->role === 'admin') {
            return $query;
        }

        if ($user->role === 'kaprodi') {
            return $query->whereHas('user', function ($q) use ($user) {
                $q->where('program_studi', $user->program_studi)
                  ->where('role', 'mahasiswa');
            });
        }

        // Default for mahasiswa and dosen: only their own data
        return $query->where('user_id', $user->id);
    }

    public function getDashboardStats(\App\Models\User $user): array
    {
        $baseQuery = $this->applyRoleFilters($this->model->newQuery(), $user);

        $totalKlasifikasi = $baseQuery->count();
        
        $totalUserQuery = \App\Models\User::where('role', '!=', 'admin');
        if ($user->role === 'kaprodi') {
            $totalUserQuery->where('program_studi', $user->program_studi);
        } elseif ($user->role !== 'admin') {
            $totalUserQuery->where('id', $user->id);
        }
        $totalUser = $totalUserQuery->count();

        $bidangTerbanyak = (clone $baseQuery)->select('prediksi_topik', \Illuminate\Support\Facades\DB::raw('COUNT(*) as total'))
            ->groupBy('prediksi_topik')
            ->orderByDesc('total')
            ->first();

        $rataAkurasi = round((clone $baseQuery)->avg('confidence_score'), 2);

        $klasifikasiBulanIni = (clone $baseQuery)->whereMonth('diklasifikasi_pada', now()->month)
            ->whereYear('diklasifikasi_pada', now()->year)
            ->count();

        $persebaranBidang = (clone $baseQuery)->select('prediksi_topik', \Illuminate\Support\Facades\DB::raw('COUNT(*) as total'))
            ->groupBy('prediksi_topik')
            ->orderByDesc('total')
            ->get();

        $trenKlasifikasi = (clone $baseQuery)->select(
            \Illuminate\Support\Facades\DB::raw('MONTH(diklasifikasi_pada) as bulan'),
            \Illuminate\Support\Facades\DB::raw('COUNT(*) as total')
        )
            ->whereYear('diklasifikasi_pada', now()->year)
            ->groupBy(\Illuminate\Support\Facades\DB::raw('MONTH(diklasifikasi_pada)'))
            ->orderBy(\Illuminate\Support\Facades\DB::raw('MONTH(diklasifikasi_pada)'))
            ->get()
            ->map(function ($item) {
                return [
                    'bulan' => \Carbon\Carbon::createFromDate(null, $item->bulan, 1)
                        ->translatedFormat('F'),
                    'total' => $item->total,
                ];
            });

        $akurasiPerBidang = (clone $baseQuery)->select(
            'prediksi_topik',
            \Illuminate\Support\Facades\DB::raw('ROUND(AVG(confidence_score), 2) as rata_akurasi')
        )
            ->groupBy('prediksi_topik')
            ->orderByDesc('rata_akurasi')
            ->get();

        $distribusiConfidence = [
            'rendah' => (clone $baseQuery)->where('confidence_score', '<', 60)->count(),
            'sedang' => (clone $baseQuery)->whereBetween('confidence_score', [60, 80])->count(),
            'tinggi' => (clone $baseQuery)->where('confidence_score', '>', 80)->count(),
        ];

        $riwayatTerakhir = (clone $baseQuery)->with('user')->orderByDesc('diklasifikasi_pada')
            ->take(10)
            ->get(['user_id', 'judul', 'prediksi_topik', 'confidence_score', 'diklasifikasi_pada']);

        return [
            'total_klasifikasi' => $totalKlasifikasi,
            'total_user' => $totalUser,
            'bidang_terbanyak' => $bidangTerbanyak?->prediksi_topik,
            'klasifikasi_bulan_ini' => $klasifikasiBulanIni,
            'rata_akurasi' => $rataAkurasi,
            'persebaran_bidang' => $persebaranBidang,
            'tren_klasifikasi' => $trenKlasifikasi,
            'akurasi_per_bidang' => $akurasiPerBidang,
            'distribusi_confidence' => $distribusiConfidence,
            'riwayat_terakhir' => $riwayatTerakhir,
        ];
    }

    public function getFilteredPaginated(\App\Models\User $user, array $params, int $limit = 10)
    {
        $query = $this->applyRoleFilters($this->model->with('user'), $user);

        return $query->when($params['search'] ?? null, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('judul', 'LIKE', "%{$search}%")
                        ->orWhere('abstrak', 'LIKE', "%{$search}%")
                        ->orWhere('prediksi_topik', 'LIKE', "%{$search}%")
                        ->orWhereHas('user', function ($uq) use ($search) {
                            $uq->where('name', 'LIKE', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate($limit);
    }
}
