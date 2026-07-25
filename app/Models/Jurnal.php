<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurnal extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Relasi ke Akun (COA)
    public function coa()
    {
        return $this->belongsTo(Coa::class, 'coa_id');
    }
    
    // Relasi ke Transaksi (Opsional, buat info tambahan)
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}