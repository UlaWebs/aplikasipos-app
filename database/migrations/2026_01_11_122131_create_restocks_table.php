<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restocks', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_restock'); // Misal: INV/2026/001
            $table->date('tanggal');
            $table->bigInteger('total_pengeluaran')->default(0); // Total belanja
            $table->string('nama_supplier')->nullable(); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restocks');
    }
};