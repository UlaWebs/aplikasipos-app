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
                // Tambah kolom 'satuan' setelah kolom 'stok'
                // Kita kasih default 'pcs' biar data lama gak error
                $table->string('satuan')->default('pcs')->after('stok'); 
            });
        }

        public function down(): void
        {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('satuan');
            });
        }
};
