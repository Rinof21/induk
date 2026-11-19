<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\EksternalController;
use App\Http\Controllers\PengujiController;
use App\Http\Controllers\PasienStandarController;
use App\Http\Controllers\SettingPublikController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TrashController;
use App\Http\Controllers\VerifikasiController;

/*
|--------------------------------------------------------------------------
| Halaman Publik Portal Fakultas & Dosen
|--------------------------------------------------------------------------
*/

// Redirect root ke portal data dosen publik
Route::get('/', function () {
    return redirect()->route('publik.dosen');
});

Route::get('/publik', function () {
    return redirect()->route('publik.dosen');
})->name('publik.index');

// Gerbang Masuk Token Publik (Jika Proteksi Token Aktif)
Route::get('/publik/token-akses', [PublicController::class, 'tokenGate'])->name('publik.token.gate');
Route::post('/publik/token-akses/verifikasi', [PublicController::class, 'verifyToken'])->name('publik.token.verify');
Route::get('/publik/token-akses/keluar', [PublicController::class, 'logoutToken'])->name('publik.token.logout');

// Halaman Data Publik (Diproteksi middleware public.token)
Route::middleware(['public.token'])->prefix('publik')->name('publik.')->group(function () {
    Route::get('/dosen', [PublicController::class, 'index'])->name('dosen');
    Route::get('/eksternal', [PublicController::class, 'eksternal'])->name('eksternal');
    Route::get('/penguji', [PublicController::class, 'penguji'])->name('penguji');
    Route::get('/pasien-standar', [PublicController::class, 'pasienStandar'])->name('pasien-standar');
    Route::post('/ajukan-perubahan', [PublicController::class, 'ajukanPerubahan'])->name('ajukan-perubahan');
});

/*
|--------------------------------------------------------------------------
| Autentikasi Pengelola / Admin
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Dashboard Internal Admin & CRUD Data
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Excel Import & Export Dosen
    Route::get('dosen/export/xlsx', [DosenController::class, 'export'])->name('dosen.export');
    Route::get('dosen/template/xlsx', [DosenController::class, 'downloadTemplate'])->name('dosen.template');
    Route::post('dosen/import/xlsx', [DosenController::class, 'import'])->name('dosen.import');
    Route::post('dosen/bulk-delete', [DosenController::class, 'bulkDelete'])->name('dosen.bulk-delete');

    // CRUD Data Dosen Internal
    Route::resource('dosen', DosenController::class);

    // Excel Import & Export Eksternal
    Route::get('eksternal/export/xlsx', [EksternalController::class, 'export'])->name('eksternal.export');
    Route::get('eksternal/template/xlsx', [EksternalController::class, 'downloadTemplate'])->name('eksternal.template');
    Route::post('eksternal/import/xlsx', [EksternalController::class, 'import'])->name('eksternal.import');
    Route::post('eksternal/bulk-delete', [EksternalController::class, 'bulkDelete'])->name('eksternal.bulk-delete');

    // CRUD Data Eksternal
    Route::resource('eksternal', EksternalController::class);

    // CRUD Data Penguji
    Route::resource('penguji', PengujiController::class);

    // CRUD Data Pasien Standar
    Route::resource('pasien-standar', PasienStandarController::class);

    // Pengaturan Token Akses Halaman Publik
    Route::prefix('pengaturan-publik')->name('pengaturan-publik.')->group(function () {
        Route::get('/', [SettingPublikController::class, 'index'])->name('index');
        Route::post('/update', [SettingPublikController::class, 'update'])->name('update');
        Route::post('/generate-token', [SettingPublikController::class, 'generateToken'])->name('generate');
        Route::post('/toggle', [SettingPublikController::class, 'toggle'])->name('toggle');
    });

    // Manajemen Pengguna & Hak Akses (Spatie Permission)
    Route::resource('users', UserController::class);

    // Kotak Sampah & Pemulihan Data (Soft Delete & Trash)
    Route::prefix('trash')->name('trash.')->group(function () {
        Route::get('/', [TrashController::class, 'index'])->name('index');
        Route::post('/{tipe}/restore/{id}', [TrashController::class, 'restore'])->name('restore');
        Route::delete('/{tipe}/force-delete/{id}', [TrashController::class, 'forceDelete'])->name('force-delete');
        Route::post('/{tipe}/restore-all', [TrashController::class, 'restoreAll'])->name('restore-all');
        Route::post('/{tipe}/empty', [TrashController::class, 'emptyTrash'])->name('empty');
    });

    // Verifikasi Pengajuan Perubahan Data dari Publik
    Route::prefix('verifikasi')->name('verifikasi.')->group(function () {
        Route::get('/', [VerifikasiController::class, 'index'])->name('index');
        Route::get('/{pengajuan}', [VerifikasiController::class, 'show'])->name('show');
        Route::post('/{pengajuan}/approve', [VerifikasiController::class, 'approve'])->name('approve');
        Route::post('/{pengajuan}/reject', [VerifikasiController::class, 'reject'])->name('reject');
        Route::delete('/{pengajuan}', [VerifikasiController::class, 'destroy'])->name('destroy');
    });
});
