<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Transaction;

class ProductController extends Controller
{
public function index()
    {
        // Mengambil semua data barang
        $products = Product::latest()->get();

        // Menghitung statistik otomatis
        $stats = [
            'total_item' => $products->count(),
            'stok_aman'  => $products->where('stock', '>', 10)->count(),
            'menipis'    => $products->whereBetween('stock', [1, 10])->count(),
            'habis'      => $products->where('stock', '<=', 0)->count(),
        ];

        // [KODE BARU] Menghitung jumlah permintaan barang keluar yang belum diverifikasi
        $unverifiedCount = Transaction::where('type', 'keluar')
            ->where('is_verified', false)
            ->count();

        // Kirim data ke Dashboard.vue
        return Inertia::render('Dashboard', [
            'products' => $products,
            'stats'    => $stats,
            'unverified_count' => $unverifiedCount, // <-- [KODE BARU] Kirim ke Vue
        ]);
    }

    public function store(Request $request)
    {
        // 1. Validasi data yang masuk
        $request->validate([
            'name'     => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'unit'     => 'required|string|max:50',
            'stock'    => 'required|integer|min:0',
        ]);

        // 2. Simpan ke database
        Product::create([
            'name'     => $request->name,
            'category' => $request->category ?? 'Umum',
            'unit'     => $request->unit,
            'stock'    => $request->stock,
        ]);

        // 3. Kembali ke halaman sebelumnya (Inertia otomatis me-refresh data)
        return redirect()->back();
    }

    public function addStock(Request $request)
    {
        // 1. Validasi input data stok masuk
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'jumlah'     => 'required|integer|min:1',
        ]);

        // 2. Cari barang berdasarkan ID dan tambahkan jumlah stoknya
        $product = Product::findOrFail($request->product_id);
        $product->increment('stock', $request->jumlah);

        Transaction::create([
            'product_id' => $product->id, 
            'type' => 'masuk', 
            'quantity' => $request->jumlah, 
            'is_verified' => true, // <-- Barang masuk otomatis terverifikasi
            'keterangan' => 'Tambah Stok'
        ]);

        // 3. Kembali ke dashboard dengan data terbaru
        return redirect()->back();
    }

    public function removeStock(Request $request)
    {
        // 1. Validasi input barang keluar
        $request->validate([
            'product_id'          => 'required|exists:products,id',
            'jumlah'              => 'required|integer|min:1',
            'tujuan'              => 'required|string|max:255',
            'verifikasi_pimpinan' => 'accepted', // Wajib dicentang/disetujui
        ], [
            'verifikasi_pimpinan.accepted' => 'Konfirmasi verifikasi pimpinan wajib disetujui.',
        ]);

        $product = Product::findOrFail($request->product_id);

        // 2. Validasi agar stok tidak bernilai negatif (minus)
        if ($product->stock < $request->jumlah) {
            return redirect()->back()->withErrors([
                'jumlah' => "Stok tidak mencukupi! Stok saat ini hanya tersisa {$product->stock} {$product->unit}."
            ]);
        }

        // 3. Kurangi stok barang
        $product->decrement('stock', $request->jumlah);

        // Keterangan: Di masa mendatang, Anda bisa menyimpan data $request->tujuan 
        // dan nama pimpinan ke dalam tabel log_transaksi di sini.
        Transaction::create([
            'product_id' => $product->id, 
            'type' => 'keluar', 
            'quantity' => $request->jumlah, 
            'keterangan' => $request->tujuan,
            'is_verified' => false // <-- Barang keluar belum terverifikasi
        ]);

        return redirect()->back();
    }

    public function history(Request $request)
    {
        // Ambil data riwayat beserta nama barangnya, urutkan dari yang terbaru
        $query = Transaction::with('product')->latest();

        // Fitur Filter Bulan & Tahun
        if ($request->filled('bulan')) {
            $query->whereMonth('created_at', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $query->whereYear('created_at', $request->tahun);
        }

        return Inertia::render('Riwayat', [
            'transactions' => $query->get(),
            'filters'      => $request->only(['bulan', 'tahun'])
        ]);
    }

    // Menampilkan halaman daftar barang yang butuh verifikasi
    public function pendingVerification()
    {
        // Ambil transaksi keluar yang belum diverifikasi
        $transactions = Transaction::with('product')
            ->where('type', 'keluar')
            ->where('is_verified', false)
            ->latest()
            ->get();

        return Inertia::render('Pimpinan/Verifikasi', [
            'transactions' => $transactions
        ]);
    }

    // Memproses persetujuan verifikasi
    public function approveTransaction($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->update(['is_verified' => true]);

        return redirect()->back();
    }
}