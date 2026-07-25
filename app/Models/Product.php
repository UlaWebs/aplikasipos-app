<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    // Pastikan fillable sesuai kolom di database kamu
    protected $fillable = [
        'kode_produk',
        'nama_produk',
        'kategori',
        'harga',      // Harga Jual
        'harga_beli', // Harga Beli (dari migration tambahanmu)
        'stok',
        'satuan',     // (dari migration tambahanmu)
        'gambar',
    ];

    // Relasi ke Detail Pembelian (Satu produk bisa ada di banyak struk restock)
    public function restockDetails()
    {
        return $this->hasMany(RestockDetail::class);
    }

    // Relasi ke Stok Awal/Opname (Satu produk bisa punya histori penyesuaian stok)
    public function stockAdjustments()
    {
        return $this->hasMany(StockAdjustment::class);
    }
}
