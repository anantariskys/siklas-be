<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\RiwayatKlasifikasiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/auth/social-login', [AuthController::class, 'socialLogin']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::get('dosen/{bidangPenelitian}', [DosenController::class, 'getDosenByBidang']);

    Route::prefix('riwayat-klasifikasi')->group(function () {
        Route::get('/', [RiwayatKlasifikasiController::class, 'index']);
        Route::get('/user/{userId}', [RiwayatKlasifikasiController::class, 'findByUser']);
        Route::post('/', [RiwayatKlasifikasiController::class, 'store']);
        Route::get('/{id}', [RiwayatKlasifikasiController::class, 'show']);
        Route::delete('/{id}', [RiwayatKlasifikasiController::class, 'destroy']);
    });
});
