<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KamarController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\PenyewaController;

Route::get('/', function () {
    return redirect()->route('kamar.index');
});

Route::get('/dashboard', function () {
    $stats = [
        'total_kamar'      => \Illuminate\Support\Facades\DB::table('kamars')->count(),
        'kamar_tersedia'   => \Illuminate\Support\Facades\DB::table('kamars')->where('status', 'tersedia')->count(),
        'kamar_terisi'     => \Illuminate\Support\Facades\DB::table('kamars')->where('status', 'terisi')->count(),
        'total_penyewa'    => \App\Models\Penyewa::count(),
        'penyewa_aktif'    => \App\Models\Penyewa::where('status', 'aktif')->count(),
        'total_karyawan'   => \App\Models\Karyawan::where('status_kerja', 'aktif')->count(),
        'pendapatan_bulan' => \App\Models\Penyewa::whereMonth('tanggal_masuk', now()->month)
                                ->whereYear('tanggal_masuk', now()->year)
                                ->sum('total_harga'),
        'segera_habis'     => \App\Models\Penyewa::where('status', 'aktif')
                                ->whereDate('tanggal_keluar', '<=', now()->addDays(7))
                                ->count(),
    ];
    return view('dashboard', compact('stats'));
})->name('dashboard');


Route::resource('kamar', KamarController::class);

// Laporan kamar tersedia
Route::get('/kamar-laporan', [KamarController::class, 'laporan'])
     ->name('kamar.laporan');

// Update status kamar cepat (AJAX)
Route::patch('/kamar/{id}/status', [KamarController::class, 'updateStatus'])
     ->name('kamar.update-status');



Route::resource('karyawan', KaryawanController::class);

// Non-aktifkan karyawan
Route::patch('/karyawan/{id}/toggle-status', [KaryawanController::class, 'toggleStatus'])
     ->name('karyawan.toggle-status');


Route::resource('penyewa', PenyewaController::class);

// Checkout penyewa (akhiri kontrak)
Route::post('/penyewa/{id}/checkout', [PenyewaController::class, 'checkout'])
     ->name('penyewa.checkout');

// Perpanjang kontrak penyewa
Route::get('/penyewa/{id}/perpanjang', [PenyewaController::class, 'formPerpanjang'])
     ->name('penyewa.perpanjang.form');
Route::post('/penyewa/{id}/perpanjang', [PenyewaController::class, 'perpanjang'])
     ->name('penyewa.perpanjang');

// Laporan pendapatan
Route::get('/penyewa-laporan', [PenyewaController::class, 'laporan'])
     ->name('penyewa.laporan');


// ═══════════════════════════════════════════════
// SETTING ROUTES
// ═══════════════════════════════════════════════
Route::get('/setting', function () {
    return view('setting.index');
})->name('setting.index');

Route::post('/setting/update', function (\Illuminate\Http\Request $request) {
    return redirect()->route('setting.index')->with('success', 'Pengaturan berhasil disimpan!');
})->name('setting.update');