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
    Schema::table('products', function (Blueprint $table) {
        // Tambah kolom harga_beli SEBELUM harga (jual) biar urut
        $table->bigInteger('harga_beli')->default(0)->after('kategori');
    });
}

public function down(): void
{
    Schema::table('products', function (Blueprint $table) {
        $table->dropColumn('harga_beli');
    });
}
};
