<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // Nama Barang
        $table->string('category')->nullable(); // Kategori (ATK, Elektronik, dll)
        $table->integer('stock')->default(0); // Jumlah Stok
        $table->string('unit')->default('Pcs'); // Satuan (Pcs, Box, Rim)
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
