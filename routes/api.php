<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiDokterController;
use App\Http\Controllers\Api\ApiPasienController;
use App\Http\Controllers\Api\ApiRuanganController;
use App\Http\Controllers\Api\ApiPemeriksaanController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware(['api-key'])->group(function () {
    Route::post('/pasien', [ApiPasienController::class, 'store'])->name('api.pasien.store');
    Route::post('/pasien/multilple', [ApiPasienController::class, 'storeMultiple'])->name('api.pasien.store.multiple');

    Route::post('/dokter', [ApiDokterController::class, 'store'])->name('api.dokter.store');
    Route::post('/dokter/multiple', [ApiDokterController::class, 'storeMultiple'])->name('api.dokter.store.multiple');

    Route::post('/ruangan', [ApiRuanganController::class, 'store'])->name('api.ruangan.store');
    Route::post('/ruangan/multiple', [ApiRuanganController::class, 'storeMultiple'])->name('api.ruangan.store.multiple');

    Route::get('/pemeriksaan', [ApiPemeriksaanController::class, 'index'])->name('api.pemeriksaan.index');
    Route::get('/pemeriksaan/{nolab}', [ApiPemeriksaanController::class, 'show'])->name('api.pemeriksaan.show');
    Route::post('/pemeriksaan', [ApiPemeriksaanController::class, 'store'])->name('api.pemeriksaan.store');
});
