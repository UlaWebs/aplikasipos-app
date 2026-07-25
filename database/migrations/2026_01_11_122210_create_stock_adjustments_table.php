<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_adjustments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products');
            $table->integer('jumlah'); // Bisa plus (nambah), bisa minus (kurang)
            $table->enum('tipe', ['stok_awal', 'penyesuaian', 'rusak', 'hilang']); 
            $table->text('catatan')->nullable(); 
            $table->date('tanggal');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_adjustments');
    }
};