<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TipeKamarController;
use App\Http\Controllers\KamarController;
use App\Http\Controllers\TamuController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CheckinController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ProfileController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);

// ── Auth (guest) ─────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ── Rute yang membutuhkan login ───────────────────────────────────────────────
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Data Tamu
    Route::resource('tamu', TamuController::class);

    // Booking
    Route::resource('booking', BookingController::class);
    Route::post('/booking/{booking}/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');
    Route::post('/booking/{booking}/toggle-status', [BookingController::class, 'toggleStatus'])->name('booking.toggleStatus');

    // Check-In
    Route::get('/checkin', [CheckinController::class, 'index'])->name('checkin.index');
    Route::post('/checkin/{booking}', [CheckinController::class, 'proses'])->name('checkin.proses');
    Route::get('/checkin/{booking}', [CheckinController::class, 'show'])->name('checkin.show');

    // Check-Out
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/{booking}', [CheckoutController::class, 'proses'])->name('checkout.proses');
    Route::get('/checkout/{booking}', [CheckoutController::class, 'show'])->name('checkout.show');

    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export-pdf', [LaporanController::class, 'exportPdf'])->name('laporan.pdf');
    Route::get('/laporan/export-excel', [LaporanController::class, 'exportExcel'])->name('laporan.excel');

    // ── Hanya Admin ──────────────────────────────────────────────────────────
    Route::middleware('role:admin')->group(function () {

        // Master Data
        Route::resource('tipe-kamar', TipeKamarController::class);
        Route::resource('kamar', KamarController::class);

        // Manajemen User
        Route::resource('users', UserController::class);

        // Activity Log
        Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activity-log.index');
        Route::get('/activity-log/{activityLog}', [ActivityLogController::class, 'show'])->name('activity-log.show');
        Route::get('/activity-log/export/excel', [ActivityLogController::class, 'exportExcel'])->name('activity-log.excel');
        Route::get('/activity-log/export/pdf', [ActivityLogController::class, 'exportPdf'])->name('activity-log.pdf');
        Route::post('/activity-log/clear-old', [ActivityLogController::class, 'clearOld'])->name('activity-log.clear-old');

        // Settings
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    });
});
