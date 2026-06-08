<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // Tambahkan baris ini agar form bisa menyimpan data ke database
    protected $fillable = ['name', 'category', 'unit', 'stock'];
}
