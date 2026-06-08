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

        // Menghitung jumlah permintaan barang keluar yang belum diverifikasi
        $unverifiedCount = Transaction::where('type', 'keluar')
            ->where('is_verified', false)
            ->count();

        // Kirim data ke Dashboard.vue
        return Inertia::render('Dashboard', [
            'products'         => $products,
            'stats'            => $stats,
            'unverified_count' => $unverifiedCount,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'unit'     => 'required|string|max:50',
            'stock'    => 'required|integer|min:0',
        ]);

        Product::create([
            'name'     => $request->name,
            'category' => $request->category ?? 'Umum',
            'unit'     => $request->unit,
            'stock'    => $request->stock,
        ]);

        return redirect()->back();
    }

    public function addStock(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'jumlah'     => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $product->increment('stock', $request->jumlah);

        Transaction::create([
           'product_id'  => $product->id, 
        'user_id'     => auth()->id(), // <-- TAMBAHKAN INI
        'type'        => 'masuk', 
        'quantity'    => $request->jumlah, 
        'is_verified' => true, 
        'keterangan'  => 'Tambah Stok'
        ]);

        return redirect()->back();
    }

    public function removeStock(Request $request)
    {
        $request->validate([
            'product_id'          => 'required|exists:products,id',
            'jumlah'              => 'required|integer|min:1',
            'tujuan'              => 'required|string|max:255',
            'verifikasi_pimpinan' => 'accepted', 
        ], [
            'verifikasi_pimpinan.accepted' => 'Konfirmasi verifikasi pimpinan wajib disetujui.',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->stock < $request->jumlah) {
            return redirect()->back()->withErrors([
                'jumlah' => "Stok tidak mencukupi! Stok saat ini hanya tersisa {$product->stock} {$product->unit}."
            ]);
        }

        $product->decrement('stock', $request->jumlah);

        Transaction::create([
            'product_id'  => $product->id, 
            'user_id'     => auth()->id(), // <-- TAMBAHKAN INI
            'type'        => 'keluar', 
            'quantity'    => $request->jumlah, 
            'keterangan'  => $request->tujuan,
            'is_verified' => false 
        ]);

        return redirect()->back();
    }

    // Memproses persetujuan verifikasi (sudah ada sebelumnya)
    public function approveTransaction($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->update(['is_verified' => true]);

        return redirect()->back();
    }

    // --- TAMBAHKAN KODE INI DI BAWAHNYA ---
    // Memproses PENOLAKAN verifikasi
    public function rejectTransaction($id)
    {
        $transaction = Transaction::findOrFail($id);
        
        // Kembalikan stok ke data barang (karena pengeluaran dibatalkan)
        $transaction->product->increment('stock', $transaction->quantity);
        
        // Hapus data transaksi yang belum diverifikasi tersebut
        $transaction->delete();

        return redirect()->back();
    }

// PADA FUNGSI history()
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
            // [KODE YANG DIUBAH] Ganti ->get() menjadi ->paginate(10)->withQueryString()
            'transactions' => $query->paginate(10)->withQueryString(), 
            'filters'      => $request->only(['bulan', 'tahun'])
        ]);
    }

    public function pendingVerification()
    {
        $transactions = Transaction::with('product')
            ->where('type', 'keluar')
            ->where('is_verified', false)
            ->latest()
            ->get();

        // PASTIKAN FILE VUE ANDA BERADA DI: resources/js/Pages/Pimpinan/Verifikasi.vue
        return Inertia::render('Pimpinan/Verifikasi', [
            'transactions' => $transactions
        ]);
    }

}