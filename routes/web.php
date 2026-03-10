<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KamarController;
use App\Http\Controllers\KamarEloquentController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KaryawanQBController;
use App\Http\Controllers\PenyewaController;
use App\Http\Controllers\PenyewaQBController;

/*
|--------------------------------------------------------------------------
| Web Routes — KOST_AG
|--------------------------------------------------------------------------
| KAMAR    → QB  (KamarController)           → /kamar
| KAMAR    → EL  (KamarEloquentController)   → /kamar-el
| KARYAWAN → EL  (KaryawanController)        → /karyawan
| KARYAWAN → QB  (KaryawanQBController)      → /karyawan-qb
| PENYEWA  → EL  (PenyewaController)         → /penyewa
| PENYEWA  → QB  (PenyewaQBController)       → /penyewa-qb
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => redirect()->route('kamar.index'));

// DASHBOARD
Route::get('/dashboard', function () {
    $stats = [
        'total_kamar'      => \Illuminate\Support\Facades\DB::table('kamars')->count(),
        'kamar_tersedia'   => \Illuminate\Support\Facades\DB::table('kamars')->where('status', 'tersedia')->count(),
        'kamar_terisi'     => \Illuminate\Support\Facades\DB::table('kamars')->where('status', 'terisi')->count(),
        'total_penyewa'    => \App\Models\Penyewa::count(),
        'penyewa_aktif'    => \App\Models\Penyewa::where('status', 'aktif')->count(),
        'total_karyawan'   => \App\Models\Karyawan::where('status_kerja', 'aktif')->count(),
        'pendapatan_bulan' => \App\Models\Penyewa::whereMonth('tanggal_masuk', now()->month)
                                ->whereYear('tanggal_masuk', now()->year)->sum('total_harga'),
        'segera_habis'     => \App\Models\Penyewa::where('status', 'aktif')
                                ->whereDate('tanggal_keluar', '<=', now()->addDays(7))->count(),
    ];
    return view('dashboard', compact('stats'));
})->name('dashboard');


// ═══════════════════════════════════════════════
// KAMAR — Query Builder  (/kamar)
// ═══════════════════════════════════════════════
Route::resource('kamar', KamarController::class);
Route::get('/kamar-laporan',         [KamarController::class, 'laporan'])->name('kamar.laporan');
Route::patch('/kamar/{id}/status',   [KamarController::class, 'updateStatus'])->name('kamar.update-status');

// ═══════════════════════════════════════════════
// KAMAR — Eloquent ORM  (/kamar-el)
// ═══════════════════════════════════════════════
Route::resource('kamar-el', KamarEloquentController::class)->names([
    'index'   => 'kamar-el.index',
    'create'  => 'kamar-el.create',
    'store'   => 'kamar-el.store',
    'show'    => 'kamar-el.show',
    'edit'    => 'kamar-el.edit',
    'update'  => 'kamar-el.update',
    'destroy' => 'kamar-el.destroy',
]);
Route::get('/kamar-el-laporan', [KamarEloquentController::class, 'laporan'])->name('kamar-el.laporan');


// ═══════════════════════════════════════════════
// KARYAWAN — Eloquent ORM  (/karyawan)
// ═══════════════════════════════════════════════
Route::resource('karyawan', KaryawanController::class);
Route::patch('/karyawan/{id}/toggle-status', [KaryawanController::class, 'toggleStatus'])
     ->name('karyawan.toggle-status');

// ═══════════════════════════════════════════════
// KARYAWAN — Query Builder  (/karyawan-qb)
// ═══════════════════════════════════════════════
Route::resource('karyawan-qb', KaryawanQBController::class)->names([
    'index'   => 'karyawan-qb.index',
    'create'  => 'karyawan-qb.create',
    'store'   => 'karyawan-qb.store',
    'show'    => 'karyawan-qb.show',
    'edit'    => 'karyawan-qb.edit',
    'update'  => 'karyawan-qb.update',
    'destroy' => 'karyawan-qb.destroy',
]);
Route::patch('/karyawan-qb/{id}/toggle-status', [KaryawanQBController::class, 'toggleStatus'])
     ->name('karyawan-qb.toggle-status');


// ═══════════════════════════════════════════════
// PENYEWA — Eloquent ORM  (/penyewa)
// ═══════════════════════════════════════════════
Route::resource('penyewa', PenyewaController::class);
Route::post('/penyewa/{id}/checkout',  [PenyewaController::class, 'checkout'])->name('penyewa.checkout');
Route::get('/penyewa-laporan',         [PenyewaController::class, 'laporan'])->name('penyewa.laporan');

// ═══════════════════════════════════════════════
// PENYEWA — Query Builder  (/penyewa-qb)
// ═══════════════════════════════════════════════
Route::resource('penyewa-qb', PenyewaQBController::class)->names([
    'index'   => 'penyewa-qb.index',
    'create'  => 'penyewa-qb.create',
    'store'   => 'penyewa-qb.store',
    'show'    => 'penyewa-qb.show',
    'edit'    => 'penyewa-qb.edit',
    'update'  => 'penyewa-qb.update',
    'destroy' => 'penyewa-qb.destroy',
]);
Route::post('/penyewa-qb/{id}/checkout', [PenyewaQBController::class, 'checkout'])->name('penyewa-qb.checkout');
Route::get('/penyewa-qb-laporan',        [PenyewaQBController::class, 'laporan'])->name('penyewa-qb.laporan');


// ═══════════════════════════════════════════════
// SETTING
// ═══════════════════════════════════════════════
Route::get('/setting',        fn() => view('setting.index'))->name('setting.index');
Route::post('/setting/update', function (\Illuminate\Http\Request $r) {
    return redirect()->route('setting.index')->with('success', 'Pengaturan berhasil disimpan!');
})->name('setting.update');