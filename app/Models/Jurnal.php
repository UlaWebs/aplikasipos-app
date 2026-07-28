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

    // Relasi ke Restock (untuk jurnal yang berasal dari pembelian/restock)
    public function restock()
    {
        return $this->belongsTo(Restock::class);
    }

    public function getNomorReferensiAttribute(): string
    {
        $restock = $this->relationLoaded('restock') ? $this->getRelation('restock') : null;
        if ($restock) {
            return $restock->nomor_restock ?? '-';
        }

        if (!is_null($this->restock_id)) {
            return $this->restock?->nomor_restock ?? '-';
        }

        $transaction = $this->relationLoaded('transaction') ? $this->getRelation('transaction') : null;
        if ($transaction) {
            return $transaction->no_transaksi ?? '-';
        }

        if (!is_null($this->transaction_id)) {
            return $this->transaction?->no_transaksi ?? '-';
        }

        return '-';
    }
}
