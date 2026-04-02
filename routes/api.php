<?php

use App\Http\Controllers\Admin\DosenController as AdminDosenController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BidangPenelitianController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\RiwayatKlasifikasiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['message' => 'Welcome to SIKLAS API']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('testing')->group(function () {
    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'index']);
    });
    Route::prefix('dosen')->group(function () {
        Route::get('/', [AdminDosenController::class, 'index']);
    });
    Route::prefix('bidang-penelitian')->group(function () {
        Route::get('/options', [BidangPenelitianController::class, 'options']);
    });
});



Route::post('/auth/login', [AuthController::class, 'authenticate'])->middleware('throttle:login');

Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    Route::prefix('bidang-penelitian')->group(function () {
        Route::get('/options', [BidangPenelitianController::class, 'options']);
    });
    
    // Unified Dashboard and Riwayat
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::prefix('riwayat-klasifikasi')->group(function () {
        Route::get('/', [RiwayatKlasifikasiController::class, 'index']);
        Route::post('/', [RiwayatKlasifikasiController::class, 'store']);
        Route::get('/{id}', [RiwayatKlasifikasiController::class, 'show']);
        Route::delete('/{id}', [RiwayatKlasifikasiController::class, 'destroy']);
    });

    Route::middleware('role:admin')->group(function () {
        Route::prefix('admin')->group(function () {
            Route::prefix('user')->group(function () {
                Route::get('/', [UserController::class, 'index']);
                Route::post('/', [UserController::class, 'store']);
                Route::delete('/{id}', [UserController::class, 'destroy']);
                Route::put('/{id}', [UserController::class, 'update']);
            });

            Route::prefix('dosen')->group(function () {
                Route::get('/', [AdminDosenController::class, 'index']);
                Route::post('/', [AdminDosenController::class, 'store']);
                Route::put('/{id}', [AdminDosenController::class, 'update']);
                Route::delete('/{id}', [AdminDosenController::class, 'destroy']);
            });
        });
    });

    Route::middleware('role:mahasiswa,dosen')->group(function () {
        Route::get('dosen/{bidangPenelitian}', [DosenController::class, 'getDosenByBidang']);
    });
});
