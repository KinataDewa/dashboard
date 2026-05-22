<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Mahasiswa\DashboardController as MhsDashboard;
use App\Http\Controllers\Mahasiswa\NilaiController;
use App\Http\Controllers\Mahasiswa\AbsensiController;
use App\Http\Controllers\Dosen\DashboardController as DosenDashboard;
use App\Http\Controllers\Dosen\KelasController as DosenKelasController;
use App\Http\Controllers\Dosen\BerisikoController as DosenBerisikoController; 
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\MahasiswaController as AdminMahasiswaController;
use App\Http\Controllers\Admin\DosenController as AdminDosenController;
use App\Http\Controllers\Admin\MatkulController;
use App\Http\Controllers\Admin\KelasController as AdminKelasController;
use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\Admin\KompensasiController;
use App\Http\Controllers\Admin\AnalitikController;
use App\Http\Controllers\Admin\BerisikoController as AdminBerisikoController;
use App\Http\Controllers\Admin\KirimPeringatanController;

// ── Root redirect ────────────────────────────────────────
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->hasRole('admin'))     return redirect()->route('admin.dashboard');
        if ($user->hasRole('dosen'))     return redirect()->route('dosen.dashboard');
        if ($user->hasRole('mahasiswa')) return redirect()->route('mahasiswa.dashboard');
    }
    return redirect()->route('login');
});

// ── Auth routes ──────────────────────────────────────────
Auth::routes(['register' => false]);

// ── MAHASISWA ────────────────────────────────────────────
Route::middleware(['auth', 'role.mahasiswa'])
    ->prefix('mahasiswa')
    ->name('mahasiswa.')
    ->group(function () {
        Route::get('/dashboard', [MhsDashboard::class,    'index'])->name('dashboard');
        Route::get('/nilai',     [NilaiController::class,  'index'])->name('nilai');
        Route::get('/absensi',   [AbsensiController::class,'index'])->name('absensi');
    });

// ── DOSEN DPA ────────────────────────────────────────────
Route::middleware(['auth', 'role.dosen'])
    ->prefix('dosen')
    ->name('dosen.')
    ->group(function () {
        Route::get('/dashboard',      [DosenDashboard::class,      'index'])->name('dashboard');
        Route::get('/kelas',          [DosenKelasController::class, 'index'])->name('kelas');
        Route::get('/mahasiswa/{id}', [DosenKelasController::class, 'detail'])->name('mahasiswa.detail');
        Route::get('/berisiko',       [DosenBerisikoController::class, 'index'])->name('berisiko.index'); // ← di dalam group
    });

// ── ADMIN ────────────────────────────────────────────────
Route::middleware(['auth', 'role.admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard',         [AdminDashboard::class, 'index'])->name('dashboard');
        Route::post('/kirim-peringatan', [AdminDashboard::class, 'kirimPeringatan'])->name('kirim.peringatan');

        Route::get('/analitik',            [AnalitikController::class, 'index'])->name('analitik.index');
        Route::get('/analitik/chart-data', [AnalitikController::class, 'chartData'])->name('analitik.chart-data');

        Route::get('/berisiko', [AdminBerisikoController::class, 'index'])->name('berisiko.index'); // ← di dalam group

        // Import data
        Route::prefix('import')->name('import.')->group(function () {
            Route::get('/',                [ImportController::class, 'index'])->name('index');
            Route::post('/nilai',          [ImportController::class, 'nilai'])->name('nilai');
            Route::post('/absensi',        [ImportController::class, 'absensi'])->name('absensi');
            Route::post('/jadwal',         [ImportController::class, 'jadwal'])->name('jadwal');
            Route::post('/mahasiswa',      [ImportController::class, 'mahasiswa'])->name('mahasiswa');
            Route::post('/dosen',          [ImportController::class, 'dosen'])->name('dosen');
            Route::post('/matkul',         [ImportController::class, 'matkul'])->name('matkul');
            Route::post('/kelas',          [ImportController::class, 'kelas'])->name('kelas');
            Route::get('/template/{type}', [ImportController::class, 'downloadTemplate'])->name('template');
        });

        Route::prefix('kompensasi')->name('kompensasi.')->group(function () {
            Route::get('/',                        [KompensasiController::class, 'index'])->name('index');
            Route::get('/create',                  [KompensasiController::class, 'create'])->name('create');
            Route::post('/',                       [KompensasiController::class, 'store'])->name('store');
            Route::get('/{kompensasi}',            [KompensasiController::class, 'show'])->name('show');
            Route::post('/{kompensasi}/ttd-admin', [KompensasiController::class, 'ttdAdmin'])->name('ttd-admin');
            Route::post('/{kompensasi}/ttd-kajur', [KompensasiController::class, 'ttdKajur'])->name('ttd-kajur');
            Route::delete('/{kompensasi}',         [KompensasiController::class, 'destroy'])->name('destroy');
        });

        Route::get('/kirim-peringatan', [KirimPeringatanController::class, 'index'])->name('kirim-peringatan.index');
        Route::post('/kirim-peringatan/satu', [KirimPeringatanController::class, 'kirimSatu'])->name('kirim-peringatan.satu');
        Route::post('/kirim-peringatan/massal', [KirimPeringatanController::class, 'kirimMassal'])->name('kirim-peringatan.massal');
        
        // CRUD resources
        Route::resource('mahasiswa', AdminMahasiswaController::class);
        Route::resource('dosen',     AdminDosenController::class);
        Route::resource('matkul',    MatkulController::class);
        Route::resource('kelas',     AdminKelasController::class);
    });