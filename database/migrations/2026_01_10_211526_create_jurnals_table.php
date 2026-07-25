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
        Schema::create('jurnals', function (Blueprint $table) {
            $table->id();
            // Relasi ke transaksi (biar tau jurnal ini dari struk yg mana)
            $table->foreignId('transaction_id')->nullable()->constrained('transactions')->onDelete('cascade');
            // Relasi ke COA (Debit/Kredit akun apa?)
            $table->foreignId('coa_id')->constrained('coas');
            $table->date('tanggal');
            $table->string('keterangan')->nullable(); // misal: "Penjualan TRX-001"
            $table->bigInteger('debit')->default(0);
            $table->bigInteger('kredit')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurnals');
    }
};
