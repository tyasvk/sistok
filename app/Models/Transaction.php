<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['product_id', 'type', 'quantity', 'keterangan'];

    // Relasi ke tabel Product
    public function product() {
        return $this->belongsTo(Product::class);
    }
}
