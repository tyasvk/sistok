<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController; // INI WAJIB DITAMBAHKAN
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Gabungkan semua rute yang butuh login (auth) ke dalam satu grup ini
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Route Dashboard (Menggunakan ProductController)
    Route::get('/dashboard', [ProductController::class, 'index'])->name('dashboard');

    // Route Profile bawaan Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Route Halaman Barang Masuk
    Route::get('/barang-masuk', function () {
        return Inertia::render('BarangMasuk');
    })->name('barang-masuk.index');

    // Route Halaman Barang Keluar
    Route::get('/barang-keluar', function () {
        return Inertia::render('BarangKeluar');
    })->name('barang-keluar.index');

    // Rute untuk menyimpan Barang Baru
    Route::post('/barang-baru', [ProductController::class, 'store'])->name('barang.store');
    // Rute untuk menambah stok barang eksisting
    Route::post('/barang-tambah-stok', [ProductController::class, 'addStock'])->name('barang.tambah-stok');

    // Rute untuk memproses pengurangan stok (Barang Keluar)
    Route::post('/barang-keluar', [ProductController::class, 'removeStock'])->name('barang.keluar');

    Route::get('/riwayat', [ProductController::class, 'history'])->name('riwayat.index');

});

// Route khusus Admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return 'Selamat datang di Dashboard Admin Sistok!';
    })->name('admin.dashboard');
});

// Route khusus Pimpinan
Route::middleware(['auth', 'pimpinan'])->group(function () {
    // Halaman list verifikasi
    Route::get('/pimpinan/verifikasi', [ProductController::class, 'pendingVerification'])->name('pimpinan.verifikasi');
    
    // Aksi tombol "Setujui"
    Route::patch('/pimpinan/verifikasi/{id}', [ProductController::class, 'approveTransaction'])->name('pimpinan.approve');
});

require __DIR__.'/auth.php';