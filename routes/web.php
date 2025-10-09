<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ReportFeedbackController;
use App\Http\Controllers\ResponseController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\UserController;
use App\Models\Response;
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

Route::get('/survey/preview/{uid}', [SurveyController::class, 'preview'])->withoutMiddleware(['auth']);
Route::post('/response/feedback/store', [ResponseController::class, 'storeFeedback'])->withoutMiddleware(['auth']);

// route with auth middleware
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [LoginController::class, 'profile'])->name('profile');
    Route::post('/profile', [LoginController::class, 'saveProfile'])->name('saveProfile');

    Route::get('/home', [DashboardController::class, 'index'])->name('home');

    Route::middleware(['client'])->group(function () {
        Route::middleware(['roles:admin,supervisor'])->group(function () {
            Route::group(['prefix' => 'location'], function () {
                Route::get('/', [LocationController::class, 'index'])->name('location.index');
                Route::get('/data', [LocationController::class, 'getData'])->name('location.data');
                Route::post('/store', [LocationController::class, 'store'])->name('location.store');
                Route::put('/update/{uid}', [LocationController::class, 'update'])->name('location.update');
                Route::delete('/delete/{uid}', [LocationController::class, 'destroy'])->name('location.destroy');
            });

            Route::group(['prefix' => 'question'], function () {
                Route::get('/', [QuestionController::class, 'index'])->name('question.index');
                Route::get('/data', [QuestionController::class, 'getData'])->name('question.data');
                Route::post('/store', [QuestionController::class, 'store'])->name('question.store');
                Route::put('/update/{uid}', [QuestionController::class, 'update'])->name('question.update');
                Route::delete('/delete/{uid}', [QuestionController::class, 'destroy'])->name('question.destroy');
            });

            Route::group(['prefix' => 'templates'], function () {
                Route::get('/', [TemplateController::class, 'index'])->name('templates.index');
                Route::get('/data', [TemplateController::class, 'getData'])->name('templates.data');
                Route::post('/store', [TemplateController::class, 'store'])->name('templates.store');
                Route::post('/update/{uid}', [TemplateController::class, 'update'])->name('templates.update');
                Route::delete('/delete/{uid}', [TemplateController::class, 'destroy'])->name('templates.destroy');
            });

            Route::group(['prefix' => 'survey'], function () {
                Route::get('/', [SurveyController::class, 'index'])->name('survey.index');
                Route::get('/data', [SurveyController::class, 'getData'])->name('survey.data');
                Route::post('/store', [SurveyController::class, 'store'])->name('survey.store');
                Route::post('/update/{uid}', [SurveyController::class, 'update'])->name('survey.update');
                Route::delete('/delete/{uid}', [SurveyController::class, 'destroy'])->name('survey.destroy');
                Route::get('/details/{id}', [SurveyController::class, 'details'])->name('survey.details');
            });

            Route::group(['prefix' => 'report-feedback'], function () {
                Route::get('/', [ReportFeedbackController::class, 'index'])->name('report-feedback.index');
                Route::get('/export/excel', [ReportFeedbackController::class, 'exportExcel'])->name('report-feedback.excel');
                Route::get('/export/pdf', [ReportFeedbackController::class, 'exportPdf'])->name('report-feedback.pdf');
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
