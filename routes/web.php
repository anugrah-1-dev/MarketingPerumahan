<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TrackingController;

Route::get('/',                      [PageController::class, 'landing'])->name('landing');
Route::get('/login',                 [PageController::class, 'login'])->name('login');
Route::post('/login',                [PageController::class, 'authenticate'])->name('login.post');
Route::post('/logout',               [PageController::class, 'logout'])->name('logout');
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/',          fn() => redirect()->route('admin.dashboard'));
    Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');
    Route::get('/tracking',               [TrackingController::class, 'index'])->name('tracking');
    Route::get('/tracking/data',           [TrackingController::class, 'data'])->name('tracking.data');
    Route::patch('/tracking/{id}/status',  [TrackingController::class, 'updateStatus'])->name('tracking.status');
    Route::get('/closing',                 fn() => view('admin.closing'))->name('closing');

    // ── Agent pages & API ──────────────────────────────────────────────────
    Route::get('/agents',                [AgentController::class, 'index'])->name('agents');
    Route::post('/agents',               [AgentController::class, 'store'])->name('agents.store');
    Route::put('/agents/{id}',           [AgentController::class, 'update'])->name('agents.update');
    Route::delete('/agents/{id}',        [AgentController::class, 'destroy'])->name('agents.destroy');
    Route::patch('/agents/{id}/status',  [AgentController::class, 'toggleStatus'])->name('agents.toggle');

    // ── Pengaturan ─────────────────────────────────────────────────────────
    Route::get('/settings',              [SettingController::class, 'index'])->name('settings');
    Route::post('/settings',             [SettingController::class, 'update'])->name('settings.update');
});

Route::get('/unit-tersedia',         [PageController::class, 'unitTersedia'])->name('unit-tersedia');
Route::get('/site-plan',             [PageController::class, 'sitePlan'])->name('site-plan');
Route::get('/detail-rumah/{blok?}',  [PageController::class, 'detailRumah'])->name('detail-rumah');
Route::get('/booking/{blok?}',       [PageController::class, 'formBooking'])->name('form-booking');
Route::post('/booking',              [PageController::class, 'storeBooking'])->name('store-booking');

// Catat klik WA (publik, tanpa auth)
Route::post('/wa-click',             [TrackingController::class, 'record'])->name('wa-click.record');

// -------------------------------------------------------
// Dynamic agent route – HARUS di paling bawah agar tidak
// menimpa route-route spesifik di atas.
// Contoh: /anugrah, /fajar, /rizky
// -------------------------------------------------------
Route::get('/{nama}',                [PageController::class, 'agentLanding'])->name('agent-landing');
