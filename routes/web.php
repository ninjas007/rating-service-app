<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Master\RuanganController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/monitoring', [DashboardController::class, 'monitoring'])
    ->withoutMiddleware(['auth'])
    ->name('monitoring');

// kalau sudah login redirect ke dashboard
Route::get('/', function () {
    return redirect('/home');
})->middleware('auth');

Route::post('/login', [LoginController::class, 'store'])->name('login');

// route with auth middleware
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [LoginController::class, 'profile'])->name('profile');
    Route::post('/profile', [LoginController::class, 'saveProfile'])->name('saveProfile');

    Route::get('/home', [DashboardController::class, 'index'])->name('home');

    Route::middleware(['client'])->group(function () {

        Route::middleware(['roles:admin,supervisor'])->group(function () {
            // parameter
            Route::middleware(['roles:admin'])->group(function () {

                // master
                Route::group(['prefix' => 'master'], function () {
                    Route::group(['prefix' => 'ruangan'], function () {
                        Route::get('/', [RuanganController::class, 'index'])->name('master.ruangan.index');
                        Route::get('/data', [RuanganController::class, 'getData'])->name('master.ruangan.data');
                        Route::post('/store', [RuanganController::class, 'store'])->name('master.ruangan.store');
                        Route::put('/update/{uid}', [RuanganController::class, 'update'])->name('master.ruangan.update');
                        Route::delete('/delete/{uid}', [RuanganController::class, 'destroy'])->name('master.ruangan.destroy');
                    });
                });
            });
        });

        Route::middleware(['roles:admin,supervisor'])->group(function () {
            Route::group(['prefix' => 'users'], function () {
                Route::get('/', [UserController::class, 'index'])->name('users.index');
                Route::get('/data', [UserController::class, 'getData'])->name('users.data');
                Route::post('/store', [UserController::class, 'store'])->name('users.store');
                Route::put('/update/{uid}', [UserController::class, 'update'])->name('users.update');
                Route::post('/delete/{uid}', [UserController::class, 'destroy'])->name('users.destroy');
            });
        });


        // hanya admin yang bisa akses setting middleware
        Route::middleware(['admin'])->group(function () {
            // setting
            Route::group(['prefix' => 'setting'], function () {

                Route::get('/', [SettingController::class, 'index'])->name('setting.index');
                Route::get('/general', [SettingController::class, 'general'])->name('setting.general');
                Route::post('/general', [SettingController::class, 'saveGeneral'])->name('setting.saveGeneral');
            });
        });
    });
});

// Route::get('/hasil', [HL7LabResultController::class, 'index']);
