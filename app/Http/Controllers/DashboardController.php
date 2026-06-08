<?php

namespace App\Http\Controllers;

use App\Models\Product; // Pastikan Anda sudah punya model Product
use Inertia\Inertia;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Mengambil data asli dari database
        $products = Product::latest()->get();

        // Menghitung statistik secara otomatis
        $stats = [
            'total_item' => $products->count(),
            'stok_aman'  => $products->where('stock', '>', 10)->count(),
            'menipis'    => $products->whereBetween('stock', [1, 10])->count(),
            'habis'      => $products->where('stock', '<=', 0)->count(),
        ];

        return Inertia::render('Dashboard', [
            'products' => $products,
            'stats'    => $stats
        ]);
    }
}