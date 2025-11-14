<?php

namespace App\Http\Controllers;

use App\Models\RiwayatKlasifikasi;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class RiwayatKlasifikasiController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $riwayat = RiwayatKlasifikasi::with('user')->latest()->get();
        return $this->successResponse($riwayat, 'Daftar riwayat klasifikasi berhasil diambil');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'judul' => 'nullable|string',
            'abstrak' => 'nullable|string',
            'prediksi_topik' => 'required|string',
            'confidence_score' => 'nullable|numeric',
        ]);

        try {
            $riwayat = RiwayatKlasifikasi::create([
                ...$validated,
                'diklasifikasi_pada' => now(),
            ]);

            return $this->successResponse($riwayat, 'Riwayat klasifikasi berhasil disimpan', 201);
        } catch (\Exception $e) {
            return $this->errorResponse('Gagal menyimpan riwayat klasifikasi', $e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        $riwayat = RiwayatKlasifikasi::with('user')->find($id);

        if (!$riwayat) {
            return $this->errorResponse('Riwayat klasifikasi tidak ditemukan', null, 404);
        }

        return $this->successResponse($riwayat, 'Detail riwayat klasifikasi berhasil diambil');
    }

    public function destroy($id)
    {
        $riwayat = RiwayatKlasifikasi::find($id);

        if (!$riwayat) {
            return $this->errorResponse('Riwayat klasifikasi tidak ditemukan', null, 404);
        }

        try {
            $riwayat->delete();
            return $this->successResponse(null, 'Riwayat klasifikasi berhasil dihapus');
        } catch (\Exception $e) {
            return $this->errorResponse('Gagal menghapus riwayat klasifikasi', $e->getMessage(), 500);
        }
    }

    /**
     * Menampilkan semua riwayat klasifikasi berdasarkan user_id
     */
    public function findByUser($userId)
    {
        try {
            $user = User::findOrFail($userId);
        } catch (\Exception $e) {
            return $this->errorResponse('User tidak ditemukan', null, 404);
        }

        $riwayat = RiwayatKlasifikasi::with('user')
            ->where('user_id', $userId)
            ->latest()
            ->get();

        if ($riwayat->isEmpty()) {
            return $this->successResponse([], 'Belum ada riwayat klasifikasi untuk user ini', 200);
        }

        return $this->successResponse($riwayat, 'Riwayat klasifikasi berdasarkan user berhasil diambil');
    }
}
