<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restock_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restock_id')->constrained('restocks')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products'); // Nyambung ke tabel produk kamu
            $table->integer('jumlah');
            $table->bigInteger('harga_beli_satuan'); // Penting buat hitung HPP
            $table->bigInteger('subtotal'); // jumlah * harga_beli
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restock_details');
    }
};