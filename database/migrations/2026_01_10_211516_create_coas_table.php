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
        Schema::create('coas', function (Blueprint $table) {
            $table->id();
            $table->string('kode_akun')->unique(); // nomor_akun (misal: 111)
            $table->string('nama_akun'); // misal: Kas
            $table->string('header_akun'); // misal: Aset, Kewajiban, Ekuitas
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coas');
    }
};
