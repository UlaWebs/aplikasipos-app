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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel users (kasir yg input)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); 
            $table->dateTime('tanggal_transaksi');
            $table->string('no_transaksi')->unique(); // Biar ada kode unik misal: TRX-001
            $table->bigInteger('total_harga');
            $table->bigInteger('bayar')->default(0); // Uang yg diterima
            $table->bigInteger('kembalian')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
