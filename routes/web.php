<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TipeRumahController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\AffiliateController;
use App\Http\Controllers\SocialMediaController;
use App\Http\Controllers\HeroSlideController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\ClosingController;
use App\Http\Controllers\ClientDataController;

Route::get('/',                      [PageController::class, 'landing'])->name('landing');
Route::get('/login',                 [PageController::class, 'login'])->name('login');
Route::post('/login',                [PageController::class, 'authenticate'])->middleware('throttle:login')->name('login.post');
Route::post('/logout',               [PageController::class, 'logout'])->name('logout');

Route::get('/storage/{path}', function (string $path) {
    $fullPath = storage_path('app/public/' . $path);
    if (! file_exists($fullPath)) {
        abort(404);
    }
    return response()->file($fullPath);
})->where('path', '.*')->name('storage.serve');

Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/',          fn() => redirect()->route('admin.dashboard'));
    Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');
    Route::get('/dashboard/data', [DashboardController::class, 'data'])->name('dashboard.data');
    Route::get('/tracking',               [TrackingController::class, 'index'])->name('tracking');
    Route::get('/tracking/data',           [TrackingController::class, 'data'])->name('tracking.data');
    Route::patch('/tracking/{id}/status',  [TrackingController::class, 'updateStatus'])->name('tracking.status');
    Route::get('/closing',                          [ClosingController::class, 'index'])->name('closing');
    Route::post('/closing',                         [ClosingController::class, 'store'])->name('closing.store');
    Route::put('/closing/{id}',                     [ClosingController::class, 'update'])->name('closing.update');
    Route::patch('/closing/{id}/komisi-status',     [ClosingController::class, 'updateKomisiStatus'])->name('closing.komisi-status');
    Route::delete('/closing/{id}',                  [ClosingController::class, 'destroy'])->name('closing.destroy');
    Route::get('/client-data',                      [ClientDataController::class, 'index'])->name('client-data');
    Route::patch('/client-data/{id}/status',        [ClientDataController::class, 'updateStatus'])->name('client-data.status');
    Route::get('/agents',                [AgentController::class, 'index'])->name('agents');
    Route::get('/agents/data',           [AgentController::class, 'data'])->name('agents.data');
    Route::get('/agents/{id}/detail',    [AgentController::class, 'show'])->name('agents.show');
    Route::post('/agents',               [AgentController::class, 'store'])->name('agents.store');
    Route::put('/agents/{id}',           [AgentController::class, 'update'])->name('agents.update');
    Route::delete('/agents/{id}',        [AgentController::class, 'destroy'])->name('agents.destroy');
    Route::patch('/agents/{id}/status',  [AgentController::class, 'toggleStatus'])->name('agents.toggle');
    Route::get('/pengisian-data',        [PageController::class, 'pengisianDataAdmin'])->name('pengisian-data');
    Route::post('/pengisian-data',       [PageController::class, 'storeClientDataAdmin'])->name('pengisian-data.store');
    Route::get('/settings',              [SettingController::class, 'index'])->name('settings');
    Route::post('/settings',             [SettingController::class, 'update'])->name('settings.update');
    Route::get('/denah',                 [SettingController::class, 'denah'])->name('denah');
    Route::post('/denah',                [SettingController::class, 'updateDenah'])->name('denah.update');
    Route::get('/lokasi-video',          [SettingController::class, 'lokasiVideo'])->name('lokasi-video');
    Route::post('/lokasi-video',         [SettingController::class, 'updateLokasiVideo'])->name('lokasi-video.update');
    Route::delete('/lokasi-video',       [SettingController::class, 'deleteLokasiVideo'])->name('lokasi-video.delete');
    Route::get('/users',                 [UserController::class, 'index'])->name('users');
    Route::post('/users',                [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{id}',            [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}',         [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/tipe-rumah',            [TipeRumahController::class, 'index'])->name('tipe-rumah');
    Route::post('/tipe-rumah',           [TipeRumahController::class, 'store'])->name('tipe-rumah.store');
    Route::put('/tipe-rumah/{id}',       [TipeRumahController::class, 'update'])->name('tipe-rumah.update');
    Route::delete('/tipe-rumah/{id}',    [TipeRumahController::class, 'destroy'])->name('tipe-rumah.destroy');
    Route::get('/social-media',               [SocialMediaController::class, 'index'])->name('social-media');
    Route::post('/social-media',              [SocialMediaController::class, 'store'])->name('social-media.store');
    Route::put('/social-media/{id}',          [SocialMediaController::class, 'update'])->name('social-media.update');
    Route::delete('/social-media/{id}',       [SocialMediaController::class, 'destroy'])->name('social-media.destroy');
    Route::patch('/social-media/{id}/toggle', [SocialMediaController::class, 'toggleStatus'])->name('social-media.toggle');
    Route::get('/hero-slides',                [HeroSlideController::class, 'index'])->name('hero-slides');
    Route::post('/hero-slides',               [HeroSlideController::class, 'store'])->name('hero-slides.store');
    Route::put('/hero-slides/{id}',           [HeroSlideController::class, 'update'])->name('hero-slides.update');
    Route::delete('/hero-slides/{id}',        [HeroSlideController::class, 'destroy'])->name('hero-slides.destroy');
    Route::patch('/hero-slides/{id}/toggle',  [HeroSlideController::class, 'toggleStatus'])->name('hero-slides.toggle');
    Route::get('/units',               [UnitController::class, 'index'])->name('units');
    Route::post('/units',              [UnitController::class, 'store'])->name('units.store');
    Route::put('/units/{id}',          [UnitController::class, 'update'])->name('units.update');
    Route::delete('/units/{id}',       [UnitController::class, 'destroy'])->name('units.destroy');
    Route::patch('/units/{id}/status', [UnitController::class, 'updateStatus'])->name('units.status');
});

Route::middleware(['auth', 'role:admin'])->prefix('manager')->name('manager.')->group(function () {
    Route::get('/',          fn() => redirect()->route('manager.dashboard'));
    Route::get('/dashboard', fn() => view('manager.dashboard'))->name('dashboard');
    Route::get('/dashboard/data', [DashboardController::class, 'data'])->name('dashboard.data');
    Route::get('/tracking',               [TrackingController::class, 'index'])->name('tracking');
    Route::get('/tracking/data',           [TrackingController::class, 'data'])->name('tracking.data');
    Route::patch('/tracking/{id}/status',  [TrackingController::class, 'updateStatus'])->name('tracking.status');
    Route::get('/pengisian-data',        [PageController::class, 'pengisianDataAdmin'])->name('pengisian-data');
    Route::post('/pengisian-data',       [PageController::class, 'storeClientDataAdmin'])->name('pengisian-data.store');
    Route::get('/agents',                [AgentController::class, 'index'])->name('agents');
    Route::get('/agents/data',           [AgentController::class, 'data'])->name('agents.data');
    Route::get('/agents/{id}/detail',    [AgentController::class, 'show'])->name('agents.show');
    Route::post('/agents',               [AgentController::class, 'store'])->name('agents.store');
    Route::put('/agents/{id}',           [AgentController::class, 'update'])->name('agents.update');
    Route::delete('/agents/{id}',        [AgentController::class, 'destroy'])->name('agents.destroy');
    Route::patch('/agents/{id}/status',  [AgentController::class, 'toggleStatus'])->name('agents.toggle');
    Route::get('/settings',              [SettingController::class, 'index'])->name('settings');
    Route::post('/settings',             [SettingController::class, 'update'])->name('settings.update');
    Route::get('/denah',                 [SettingController::class, 'denah'])->name('denah');
    Route::post('/denah',                [SettingController::class, 'updateDenah'])->name('denah.update');
    Route::get('/lokasi-video',          [SettingController::class, 'lokasiVideo'])->name('lokasi-video');
    Route::post('/lokasi-video',         [SettingController::class, 'updateLokasiVideo'])->name('lokasi-video.update');
    Route::delete('/lokasi-video',       [SettingController::class, 'deleteLokasiVideo'])->name('lokasi-video.delete');
    Route::get('/tipe-rumah',            [TipeRumahController::class, 'index'])->name('tipe-rumah');
    Route::post('/tipe-rumah',           [TipeRumahController::class, 'store'])->name('tipe-rumah.store');
    Route::put('/tipe-rumah/{id}',       [TipeRumahController::class, 'update'])->name('tipe-rumah.update');
    Route::delete('/tipe-rumah/{id}',    [TipeRumahController::class, 'destroy'])->name('tipe-rumah.destroy');
    Route::get('/social-media',               [SocialMediaController::class, 'index'])->name('social-media');
    Route::post('/social-media',              [SocialMediaController::class, 'store'])->name('social-media.store');
    Route::put('/social-media/{id}',          [SocialMediaController::class, 'update'])->name('social-media.update');
    Route::delete('/social-media/{id}',       [SocialMediaController::class, 'destroy'])->name('social-media.destroy');
    Route::patch('/social-media/{id}/toggle', [SocialMediaController::class, 'toggleStatus'])->name('social-media.toggle');
    Route::get('/hero-slides',                [HeroSlideController::class, 'index'])->name('hero-slides');
    Route::post('/hero-slides',               [HeroSlideController::class, 'store'])->name('hero-slides.store');
    Route::put('/hero-slides/{id}',           [HeroSlideController::class, 'update'])->name('hero-slides.update');
    Route::delete('/hero-slides/{id}',        [HeroSlideController::class, 'destroy'])->name('hero-slides.destroy');
    Route::patch('/hero-slides/{id}/toggle',  [HeroSlideController::class, 'toggleStatus'])->name('hero-slides.toggle');
    Route::get('/units',               [UnitController::class, 'index'])->name('units');
    Route::post('/units',              [UnitController::class, 'store'])->name('units.store');
    Route::put('/units/{id}',          [UnitController::class, 'update'])->name('units.update');
    Route::delete('/units/{id}',       [UnitController::class, 'destroy'])->name('units.destroy');
    Route::patch('/units/{id}/status', [UnitController::class, 'updateStatus'])->name('units.status');
});

Route::get('/tipe-rumah',            [TipeRumahController::class, 'publicIndex'])->name('tipe-rumah.publik');
Route::get('/tipe-rumah/{id}/detail', [TipeRumahController::class, 'publicDetail'])->name('tipe-rumah.detail');
Route::get('/unit-tersedia',         [TipeRumahController::class, 'publicIndex'])->name('unit-tersedia');
Route::get('/site-plan',             [PageController::class, 'sitePlan'])->name('site-plan');
Route::get('/detail-rumah/{blok?}',  [PageController::class, 'detailRumah'])->name('detail-rumah');
Route::post('/wa-click',             [TrackingController::class, 'record'])->middleware('throttle:tracking')->name('wa-click.record');
Route::get('/ref/{code}',            [ReferralController::class, 'handle'])->name('referral');
Route::get('/{nama}',                [PageController::class, 'agentLanding'])->name('agent-landing');
if (file_exists(__DIR__.'/affiliate-leads-status.php')) {
    require __DIR__.'/affiliate-leads-status.php';
}
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TipeRumahController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\AffiliateController;
use App\Http\Controllers\SocialMediaController;
use App\Http\Controllers\HeroSlideController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\ClosingController;
use App\Http\Controllers\ClientDataController;

Route::get('/',                      [PageController::class, 'landing'])->name('landing');
Route::get('/login',                 [PageController::class, 'login'])->name('login');
Route::post('/login',                [PageController::class, 'authenticate'])->middleware('throttle:login')->name('login.post');
Route::post('/logout',               [PageController::class, 'logout'])->name('logout');

// ── Serve legacy files from storage/app/public (shared-hosting symlink fallback) ──
Route::get('/storage/{path}', function (string $path) {
    $fullPath = storage_path('app/public/' . $path);
    if (! file_exists($fullPath)) {
        abort(404);
    }
    return response()->file($fullPath);
})->where('path', '.*')->name('storage.serve');


// ── Super Admin Panel (/admin) ─────────────────────────────────────────────
Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/',          fn() => redirect()->route('admin.dashboard'));
    Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');
    Route::get('/dashboard/data', [DashboardController::class, 'data'])->name('dashboard.data');
    Route::get('/tracking',               [TrackingController::class, 'index'])->name('tracking');
    Route::get('/tracking/data',           [TrackingController::class, 'data'])->name('tracking.data');
    Route::patch('/tracking/{id}/status',  [TrackingController::class, 'updateStatus'])->name('tracking.status');
    Route::get('/closing',                          [ClosingController::class, 'index'])->name('closing');
    Route::post('/closing',                         [ClosingController::class, 'store'])->name('closing.store');
    Route::put('/closing/{id}',                     [ClosingController::class, 'update'])->name('closing.update');
    Route::patch('/closing/{id}/komisi-status',     [ClosingController::class, 'updateKomisiStatus'])->name('closing.komisi-status');
    Route::delete('/closing/{id}',                  [ClosingController::class, 'destroy'])->name('closing.destroy');

    // ── Manajemen Data Client ─────────────────────────────────────────────
    Route::get('/client-data',                      [ClientDataController::class, 'index'])->name('client-data');
    Route::patch('/client-data/{id}/status',        [ClientDataController::class, 'updateStatus'])->name('client-data.status');

    // ── Agent pages & API ──────────────────────────────────────────────────
    Route::get('/agents',                [AgentController::class, 'index'])->name('agents');
    Route::get('/agents/data',           [AgentController::class, 'data'])->name('agents.data');
    Route::get('/agents/{id}/detail',    [AgentController::class, 'show'])->name('agents.show');
    Route::post('/agents',               [AgentController::class, 'store'])->name('agents.store');
    Route::put('/agents/{id}',           [AgentController::class, 'update'])->name('agents.update');
    Route::delete('/agents/{id}',        [AgentController::class, 'destroy'])->name('agents.destroy');
    Route::patch('/agents/{id}/status',  [AgentController::class, 'toggleStatus'])->name('agents.toggle');

    // ── Pengisian Data Client ──────────────────────────────────────────────
    Route::get('/pengisian-data',        [PageController::class, 'pengisianDataAdmin'])->name('pengisian-data');
    Route::post('/pengisian-data',       [PageController::class, 'storeClientDataAdmin'])->name('pengisian-data.store');

    // ── Pengaturan ─────────────────────────────────────────────────────────
    Route::get('/settings',              [SettingController::class, 'index'])->name('settings');
    Route::post('/settings',             [SettingController::class, 'update'])->name('settings.update');

    // ── Denah Perumahan ────────────────────────────────────────────────────
    Route::get('/denah',                 [SettingController::class, 'denah'])->name('denah');
    Route::post('/denah',                [SettingController::class, 'updateDenah'])->name('denah.update');

    // ── Video Lokasi ───────────────────────────────────────────────────────
    Route::get('/lokasi-video',          [SettingController::class, 'lokasiVideo'])->name('lokasi-video');
    Route::post('/lokasi-video',         [SettingController::class, 'updateLokasiVideo'])->name('lokasi-video.update');
    Route::delete('/lokasi-video',       [SettingController::class, 'deleteLokasiVideo'])->name('lokasi-video.delete');

    // ── Manajemen Users ───────────────────────────────────────────────────
    Route::get('/users',                 [UserController::class, 'index'])->name('users');
    Route::post('/users',                [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{id}',            [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}',         [UserController::class, 'destroy'])->name('users.destroy');

    // ── Tipe Rumah ────────────────────────────────────────────────────────
    Route::get('/tipe-rumah',            [TipeRumahController::class, 'index'])->name('tipe-rumah');
    Route::post('/tipe-rumah',           [TipeRumahController::class, 'store'])->name('tipe-rumah.store');
    Route::put('/tipe-rumah/{id}',       [TipeRumahController::class, 'update'])->name('tipe-rumah.update');
    Route::delete('/tipe-rumah/{id}',    [TipeRumahController::class, 'destroy'])->name('tipe-rumah.destroy');

    // ── Social Media ──────────────────────────────────────────────────────
    Route::get('/social-media',               [SocialMediaController::class, 'index'])->name('social-media');
    Route::post('/social-media',              [SocialMediaController::class, 'store'])->name('social-media.store');
    Route::put('/social-media/{id}',          [SocialMediaController::class, 'update'])->name('social-media.update');
    Route::delete('/social-media/{id}',       [SocialMediaController::class, 'destroy'])->name('social-media.destroy');
    Route::patch('/social-media/{id}/toggle', [SocialMediaController::class, 'toggleStatus'])->name('social-media.toggle');

    // ── Hero Slides ───────────────────────────────────────────────────────
    Route::get('/hero-slides',                [HeroSlideController::class, 'index'])->name('hero-slides');
    Route::post('/hero-slides',               [HeroSlideController::class, 'store'])->name('hero-slides.store');
    Route::put('/hero-slides/{id}',           [HeroSlideController::class, 'update'])->name('hero-slides.update');
    Route::delete('/hero-slides/{id}',        [HeroSlideController::class, 'destroy'])->name('hero-slides.destroy');
    Route::patch('/hero-slides/{id}/toggle',  [HeroSlideController::class, 'toggleStatus'])->name('hero-slides.toggle');

    // ── Manajemen Unit ────────────────────────────────────────────────────
    Route::get('/units',               [UnitController::class, 'index'])->name('units');
    Route::post('/units',              [UnitController::class, 'store'])->name('units.store');
    Route::put('/units/{id}',          [UnitController::class, 'update'])->name('units.update');
    Route::delete('/units/{id}',       [UnitController::class, 'destroy'])->name('units.destroy');
    Route::patch('/units/{id}/status', [UnitController::class, 'updateStatus'])->name('units.status');
});

// ── Admin Panel (/manager) ─────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->prefix('manager')->name('manager.')->group(function () {
    Route::get('/',          fn() => redirect()->route('manager.dashboard'));
    Route::get('/dashboard', fn() => view('manager.dashboard'))->name('dashboard');
    Route::get('/dashboard/data', [DashboardController::class, 'data'])->name('dashboard.data');

    // ── Tracking ──────────────────────────────────────────────────────────
    Route::get('/tracking',               [TrackingController::class, 'index'])->name('tracking');
    Route::get('/tracking/data',           [TrackingController::class, 'data'])->name('tracking.data');
    Route::patch('/tracking/{id}/status',  [TrackingController::class, 'updateStatus'])->name('tracking.status');

    // ── Pengisian Data Client ──────────────────────────────────────────────
    Route::get('/pengisian-data',        [PageController::class, 'pengisianDataAdmin'])->name('pengisian-data');
    Route::post('/pengisian-data',       [PageController::class, 'storeClientDataAdmin'])->name('pengisian-data.store');

    // ── Agent ─────────────────────────────────────────────────────────────
    Route::get('/agents',                [AgentController::class, 'index'])->name('agents');
    Route::get('/agents/data',           [AgentController::class, 'data'])->name('agents.data');
    Route::get('/agents/{id}/detail',    [AgentController::class, 'show'])->name('agents.show');
    Route::post('/agents',               [AgentController::class, 'store'])->name('agents.store');
    Route::put('/agents/{id}',           [AgentController::class, 'update'])->name('agents.update');
    Route::delete('/agents/{id}',        [AgentController::class, 'destroy'])->name('agents.destroy');
    Route::patch('/agents/{id}/status',  [AgentController::class, 'toggleStatus'])->name('agents.toggle');

    // ── Pengaturan ─────────────────────────────────────────────────────────
    Route::get('/settings',              [SettingController::class, 'index'])->name('settings');
    Route::post('/settings',             [SettingController::class, 'update'])->name('settings.update');

    // ── Denah Perumahan ────────────────────────────────────────────────────
    Route::get('/denah',                 [SettingController::class, 'denah'])->name('denah');
    Route::post('/denah',                [SettingController::class, 'updateDenah'])->name('denah.update');

    // ── Video Lokasi ───────────────────────────────────────────────────────
    Route::get('/lokasi-video',          [SettingController::class, 'lokasiVideo'])->name('lokasi-video');
    Route::post('/lokasi-video',         [SettingController::class, 'updateLokasiVideo'])->name('lokasi-video.update');
    Route::delete('/lokasi-video',       [SettingController::class, 'deleteLokasiVideo'])->name('lokasi-video.delete');

    // ── Tipe Rumah ────────────────────────────────────────────────────────
    Route::get('/tipe-rumah',            [TipeRumahController::class, 'index'])->name('tipe-rumah');
    Route::post('/tipe-rumah',           [TipeRumahController::class, 'store'])->name('tipe-rumah.store');
    Route::put('/tipe-rumah/{id}',       [TipeRumahController::class, 'update'])->name('tipe-rumah.update');
    Route::delete('/tipe-rumah/{id}',    [TipeRumahController::class, 'destroy'])->name('tipe-rumah.destroy');

    // ── Social Media ──────────────────────────────────────────────────────
    Route::get('/social-media',               [SocialMediaController::class, 'index'])->name('social-media');
    Route::post('/social-media',              [SocialMediaController::class, 'store'])->name('social-media.store');
    Route::put('/social-media/{id}',          [SocialMediaController::class, 'update'])->name('social-media.update');
    Route::delete('/social-media/{id}',       [SocialMediaController::class, 'destroy'])->name('social-media.destroy');
    Route::patch('/social-media/{id}/toggle', [SocialMediaController::class, 'toggleStatus'])->name('social-media.toggle');

    // ── Hero Slides ───────────────────────────────────────────────────────
    Route::get('/hero-slides',                [HeroSlideController::class, 'index'])->name('hero-slides');
    Route::post('/hero-slides',               [HeroSlideController::class, 'store'])->name('hero-slides.store');
    Route::put('/hero-slides/{id}',           [HeroSlideController::class, 'update'])->name('hero-slides.update');
    Route::delete('/hero-slides/{id}',        [HeroSlideController::class, 'destroy'])->name('hero-slides.destroy');
    Route::patch('/hero-slides/{id}/toggle',  [HeroSlideController::class, 'toggleStatus'])->name('hero-slides.toggle');

    // ── Manajemen Users ───────────────────────────────────────────────────
    Route::get('/users',                 [UserController::class, 'index'])->name('users');
    Route::post('/users',                [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{id}',            [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}',         [UserController::class, 'destroy'])->name('users.destroy');

    // ── Manajemen Closing ─────────────────────────────────────────────────
    Route::get('/closing',                          [ClosingController::class, 'index'])->name('closing');
    Route::post('/closing',                         [ClosingController::class, 'store'])->name('closing.store');
    Route::put('/closing/{id}',                     [ClosingController::class, 'update'])->name('closing.update');
    Route::patch('/closing/{id}/komisi-status',     [ClosingController::class, 'updateKomisiStatus'])->name('closing.komisi-status');
    Route::delete('/closing/{id}',                  [ClosingController::class, 'destroy'])->name('closing.destroy');

    // ── Manajemen Data Client ─────────────────────────────────────────────
    Route::get('/client-data',                      [ClientDataController::class, 'index'])->name('client-data');
    Route::patch('/client-data/{id}/status',        [ClientDataController::class, 'updateStatus'])->name('client-data.status');

    // ── Manajemen Unit ────────────────────────────────────────────────────
    Route::get('/units',               [UnitController::class, 'index'])->name('units');
    Route::post('/units',              [UnitController::class, 'store'])->name('units.store');
    Route::put('/units/{id}',          [UnitController::class, 'update'])->name('units.update');
    Route::delete('/units/{id}',       [UnitController::class, 'destroy'])->name('units.destroy');
    Route::patch('/units/{id}/status', [UnitController::class, 'updateStatus'])->name('units.status');
});

// ── Affiliate Panel ──────────────────────────────────────────────────────
Route::middleware(['auth', 'role:affiliate'])->prefix('affiliate')->name('affiliate.')->group(function () {
    Route::get('/',          fn() => redirect()->route('affiliate.dashboard'));
