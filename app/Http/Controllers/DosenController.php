<?php

namespace App\Http\Controllers;

use App\Models\BidangPenelitian;
use App\Models\Dosen;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class DosenController extends Controller

{

    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Dosen $dosen)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dosen $dosen)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dosen $dosen)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dosen $dosen)
    {
        //
    }

 public function getDosenByBidang(Request $request, $bidangPenelitian)
    {
        // Cari bidang penelitian berdasarkan slug
        $bidang = BidangPenelitian::where('slug', $bidangPenelitian)
            ->with([
                'dosenMajor.major:id,nama',
                'dosenMajor.minor:id,nama',
                'dosenMinor.major:id,nama',
                'dosenMinor.minor:id,nama',
            ])
            ->first();

        if (!$bidang) {
            return $this->errorResponse('Bidang penelitian tidak ditemukan', 404);
        }

        // Gabungkan dosen major & minor
        $allDosen = $bidang->dosenMajor->merge($bidang->dosenMinor)->map(function($d) {
            return [
                'nama' => $d->nama,
                'bidang_penelitian_major' => $d->major?->nama,
                'bidang_penelitian_minor' => $d->minor?->nama,
            ];
        })->values();

        return $this->successResponse([
            'bidang' => $bidang->nama,
            'dosen' => $allDosen,
        ], 'Dosen retrieved successfully');
    }
}
