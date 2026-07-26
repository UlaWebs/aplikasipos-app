<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Tambahkan relasi ini agar 'items' bisa di-load (Eager Loading)
    public function items()
    {
        return $this->hasMany(TransactionItem::class, 'transaction_id');
    }
}
