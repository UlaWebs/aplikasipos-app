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
        Schema::table('jurnals', function (Blueprint $table) {
            $table->foreignId('restock_id')->nullable()->after('transaction_id')->constrained('restocks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jurnals', function (Blueprint $table) {
            $table->dropConstrainedForeignId('restock_id');
        });
    }
};
