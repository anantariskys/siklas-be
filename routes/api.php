<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DosenController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/auth/social-login', [AuthController::class, 'socialLogin']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('dosen/{bidangPenelitian}', [DosenController::class, 'getDosenByBidang']);
});
