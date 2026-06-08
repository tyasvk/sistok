<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
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

// Grup rute untuk semua pengguna yang sudah login
Route::middleware(['auth', 'verified'])->group(function () {
    
    // --- AKSES UMUM (Semua Role Bisa Akses) ---
    Route::get('/dashboard', [ProductController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/riwayat', [ProductController::class, 'history'])->name('riwayat.index');

    // HANYA INI YANG BISA DIAKSES USER BIASA TERKAIT STOK
    Route::get('/barang-keluar', function () {
        return Inertia::render('BarangKeluar');
    })->name('barang-keluar.index');
    Route::post('/barang-keluar', [ProductController::class, 'removeStock'])->name('barang.keluar');


    // --- AKSES KHUSUS (Hanya Admin ATAU Pimpinan) ---
    // Menggunakan middleware 'admin' (yang sudah diatur untuk mengizinkan admin & pimpinan)
    Route::middleware(['admin'])->group(function () {
        
        // Manajemen User
        Route::resource('users', UserController::class)->except(['create', 'show', 'edit']);

        // Verifikasi Barang
        Route::get('/verifikasi', [ProductController::class, 'pendingVerification'])->name('pimpinan.verifikasi');
        Route::patch('/verifikasi/{id}', [ProductController::class, 'approveTransaction'])->name('pimpinan.approve');
        
        // --- RUTE BARANG MASUK DIPINDAH KE SINI ---
        Route::get('/barang-masuk', function () {
            return Inertia::render('BarangMasuk');
        })->name('barang-masuk.index');
        Route::post('/barang-baru', [ProductController::class, 'store'])->name('barang.store');
        Route::post('/barang-tambah-stok', [ProductController::class, 'addStock'])->name('barang.tambah-stok');

        // --- TAMBAHKAN BARIS INI UNTUK TOMBOL TOLAK ---
        Route::delete('/verifikasi/{id}', [ProductController::class, 'rejectTransaction'])->name('pimpinan.reject');
        
    });

});

require __DIR__.'/auth.php';