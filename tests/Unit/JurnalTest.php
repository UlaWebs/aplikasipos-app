<?php

namespace Tests\Unit;

use App\Models\Jurnal;
use App\Models\Restock;
use App\Models\Transaction;
use Tests\TestCase;

class JurnalTest extends TestCase
{
    public function test_it_returns_restock_reference_when_available(): void
    {
        $restock = new Restock(['nomor_restock' => 'RS-001']);
        $jurnal = new Jurnal();
        $jurnal->setRelation('restock', $restock);

        $this->assertSame('RS-001', $jurnal->nomor_referensi);
    }

    public function test_it_returns_transaction_reference_when_available(): void
    {
        $transaction = new Transaction(['no_transaksi' => 'TRX-001']);
        $jurnal = new Jurnal();
        $jurnal->setRelation('transaction', $transaction);

        $this->assertSame('TRX-001', $jurnal->nomor_referensi);
    }
}
