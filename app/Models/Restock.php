<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restock extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_restock',
        'tanggal',
        'total_pengeluaran',
        'nama_supplier',
    ];

    // Relasi: Satu struk Restock punya banyak detail barang
    public function details()
    {
        return $this->hasMany(RestockDetail::class);
    }
}