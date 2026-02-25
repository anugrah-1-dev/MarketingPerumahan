<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;

Route::get('/',                      [PageController::class, 'landing'])->name('landing');
Route::get('/login',                 [PageController::class, 'login'])->name('login');
Route::get('/unit-tersedia',         [PageController::class, 'unitTersedia'])->name('unit-tersedia');
Route::get('/site-plan',             [PageController::class, 'sitePlan'])->name('site-plan');
Route::get('/detail-rumah/{blok?}',  [PageController::class, 'detailRumah'])->name('detail-rumah');
Route::get('/booking/{blok?}',       [PageController::class, 'formBooking'])->name('form-booking');
Route::post('/booking',              [PageController::class, 'storeBooking'])->name('store-booking');

// -------------------------------------------------------
// Dynamic agent route – HARUS di paling bawah agar tidak
// menimpa route-route spesifik di atas.
// Contoh: /anugrah, /fajar, /rizky
// -------------------------------------------------------
Route::get('/{nama}',                [PageController::class, 'agentLanding'])->name('agent-landing');
