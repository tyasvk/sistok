<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    // PASTIKAN SEMUA KOLOM INI ADA DI DALAM $fillable
    protected $fillable = [
        'product_id',
        'type',
        'quantity',
        'keterangan',
        'is_verified', // Kolom baru yang kita tambahkan sebelumnya
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}