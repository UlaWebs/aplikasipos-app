<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestockDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'restock_id',
        'product_id',
        'jumlah',
        'harga_beli_satuan',
        'subtotal',
    ];

    // Kebalikannya: Detail ini milik satu Restock (struk)
    public function restock()
    {
        return $this->belongsTo(Restock::class);
    }

    // Detail ini merujuk ke satu Produk
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}