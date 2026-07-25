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
            $table->string('kode_produk')->unique();
            $table->string('nama_produk');
            $table->string('kategori'); // Makanan/Minuman
            $table->bigInteger('harga'); // Pakai BigInt biar aman digitnya banyak
            $table->integer('stok')->default(0); // Tambahan penting buat POS
            $table->string('gambar')->nullable(); // Simpan nama filenya aja
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
