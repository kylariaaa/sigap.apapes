<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ResponseController;
use Illuminate\Support\Facades\Route;

// Route::middleware('woy')->get('/', function () {
//     return view('auth.login');
// })->name('');

// =====================
// Jalur Tamu (Belum Login)
// =====================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

// =====================
// Jalur User Login
// =====================
Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard Admin
    Route::get('/admin/dashboard', [AuthController::class, 'dashboard'])->name('admin.dashboard');
    // Dashboard Warga
    Route::get('/warga/dashboard', function () {
        return view('user.dashboard');
    })->name('user.dashboard');

    Route::get('/lapor', [ReportController::class, 'index'])->name('user.lapor');
    Route::post('/lapor', [ReportController::class, 'store'])->name('user.lapor.store');

    //Get dan wildcard (Get menampilkan data berdasarkan ID)
    Route::get('/report/{report}', [ReportController::class, 'show'])->name('report.show');
    //put dan wildcard (Put mengupdate data berdasarkan ID)
    Route::put('/report/{report}', [ReportController::class, 'update'])->name('report.update');

    Route::post('/response', [ResponseController::class, 'store'])->name('response.store');
});
