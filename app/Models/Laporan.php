<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Jurnal;
use App\Models\Coa;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Carbon\Carbon;

class Laporan extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Ambil data Jurnal Umum
     */
    public static function getJurnalUmum()
    {
        return Jurnal::with(['coa', 'transaction', 'restock'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('id', 'desc')
            ->get();
    }

    /**
     * Ambil data Buku Besar berdasarkan Akun yang dipilih
     */
    public static function getBukuBesar($akunId)
    {
        $coas = Coa::orderBy('kode_akun')->get();
        $selectedCoa = null;
        $jurnals = [];

        if ($akunId) {
            $selectedCoa = Coa::find($akunId);

            if ($selectedCoa) {
                $jurnals = Jurnal::where('coa_id', $akunId)
                    ->orderBy('tanggal', 'asc')
                    ->get();
            }
        }

        return compact('coas', 'jurnals', 'selectedCoa');
    }

    /**
     * Hitung dan rangkum Laporan Laba Rugi
     */
    public static function getLabaRugi()
    {
        $pendapatan = Jurnal::whereHas('coa', fn($q) => $q->where('kode_akun', 'like', '4%'))->sum('kredit')
                    - Jurnal::whereHas('coa', fn($q) => $q->where('kode_akun', 'like', '4%'))->sum('debit');

        $hpp = Jurnal::whereHas('coa', fn($q) => $q->where('kode_akun', 'like', '5%'))->sum('debit')
             - Jurnal::whereHas('coa', fn($q) => $q->where('kode_akun', 'like', '5%'))->sum('kredit');

        $bebanList = Coa::where('kode_akun', 'like', '6%')->get()->map(function ($akun) {
            $saldo = Jurnal::where('coa_id', $akun->id)->sum('debit') - Jurnal::where('coa_id', $akun->id)->sum('kredit');
            $akun->saldo_akhir = $saldo;
            return $akun;
        })->where('saldo_akhir', '>', 0);

        $totalBeban = $bebanList->sum('saldo_akhir');
        $labaKotor = $pendapatan - $hpp;
        $labaBersih = $labaKotor - $totalBeban;

        return compact('pendapatan', 'hpp', 'bebanList', 'totalBeban', 'labaKotor', 'labaBersih');
    }

    /**
     * Hitung Laba Bersih internal (helper untuk Neraca)
     */
    public static function getLabaBersih()
    {
        $pendapatan = Jurnal::whereHas('coa', fn($q) => $q->where('kode_akun', 'like', '4%'))->sum('kredit')
                    - Jurnal::whereHas('coa', fn($q) => $q->where('kode_akun', 'like', '4%'))->sum('debit');

        $hpp = Jurnal::whereHas('coa', fn($q) => $q->where('kode_akun', 'like', '5%'))->sum('debit')
             - Jurnal::whereHas('coa', fn($q) => $q->where('kode_akun', 'like', '5%'))->sum('kredit');

        $beban = Jurnal::whereHas('coa', fn($q) => $q->where('kode_akun', 'like', '6%'))->sum('debit')
               - Jurnal::whereHas('coa', fn($q) => $q->where('kode_akun', 'like', '6%'))->sum('kredit');

        return $pendapatan - $hpp - $beban;
    }

    /**
     * Hitung dan rangkum Laporan Neraca
     */
    public static function getNeraca()
    {
        $aset = Coa::where('kode_akun', 'like', '1%')->get()->map(function ($akun) {
            $akun->saldo = Jurnal::where('coa_id', $akun->id)->sum('debit')
                         - Jurnal::where('coa_id', $akun->id)->sum('kredit');
            return $akun;
        });
        $totalAset = $aset->sum('saldo');

        $kewajiban = Coa::where('kode_akun', 'like', '2%')->get()->map(function ($akun) {
            $akun->saldo = Jurnal::where('coa_id', $akun->id)->sum('kredit')
                         - Jurnal::where('coa_id', $akun->id)->sum('debit');
            return $akun;
        });
        $totalKewajiban = $kewajiban->sum('saldo');

        $modal = Coa::where('kode_akun', 'like', '3%')->get()->map(function ($akun) {
            $akun->saldo = Jurnal::where('coa_id', $akun->id)->sum('kredit')
                         - Jurnal::where('coa_id', $akun->id)->sum('debit');
            return $akun;
        });
        $totalModal = $modal->sum('saldo');

        $labaBersih = self::getLabaBersih();
        $totalPasiva = $totalKewajiban + $totalModal + $labaBersih;

        return compact('aset', 'totalAset', 'kewajiban', 'totalKewajiban', 'modal', 'totalModal', 'labaBersih', 'totalPasiva');
    }

    /**
     * Mengambil Laporan Penjualan berdasarkan Filter Periode
     * Sesuai struktur data pada template/gambar (No, Invoice, Kasir, Tanggal, Total, Met. Bayar, Detail Items)
     */
    public static function getPenjualan($periode = 'hari_ini')
    {
        $query = Transaction::with(['user', 'items.product'])->orderBy('created_at', 'desc');

        // Filter berdasarkan periode
        switch ($periode) {
            case 'hari_ini':
                $query->whereDate('created_at', Carbon::today());
                break;
            case 'minggu_ini':
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'bulan_ini':
                $query->whereMonth('created_at', Carbon::now()->month)
                      ->whereYear('created_at', Carbon::now()->year);
                break;
            case 'tahun_ini':
                $query->whereYear('created_at', Carbon::now()->year);
                break;
            case 'semua':
            default:
                break;
        }

        $transactions = $query->get();

        // Format data agar siap dirender atau dikirim sebagai JSON
        return $transactions->map(function ($trx, $index) {
            return [
                'no' => $index + 1,
                // Di migration namanya 'no_transaksi', bukan 'invoice_number'
                'no_invoice' => $trx->no_transaksi ?? '-',

                'kasir' => $trx->user->name ?? 'Kasir',

                // Gunakan 'tanggal_transaksi' yang ada di migration, fallback ke 'created_at'
                'tanggal' => Carbon::parse($trx->tanggal_transaksi ?? $trx->created_at)->format('Y-m-d H:i'),

                // Catatan: Kolom metode bayar tidak ada di migration 'transactions'.
                // Saya hardcode ke 'CASH' untuk sementara agar frontend tidak error.
                'metode_pembayaran' => 'CASH',

                // Di migration namanya 'total_harga'
                'total_harga' => $trx->total_harga ?? 0,

                'items' => $trx->items->map(function ($item) {
                    return [
                        // Di migration 'products' namanya 'nama_produk', bukan 'name'
                        'nama_produk' => $item->product->nama_produk ?? '-',

                        // Di migration 'transaction_items' namanya 'jumlah', bukan 'qty'
                        'qty' => $item->jumlah ?? 0,

                        // Di migration 'transaction_items' namanya 'harga_satuan'
                        'harga_satuan' => $item->harga_satuan ?? 0,

                        'subtotal' => $item->subtotal ?? ($item->jumlah * $item->harga_satuan) ?? 0,
                    ];
                })
            ];
        });
    }

    /**
     * Mengambil Laporan Pembelian (Restock) berdasarkan Filter Periode
     */
    public static function getPembelian($periode = 'hari_ini')
    {
        // Panggil relasi details dan product dari RestockDetail
        $query = Restock::with(['details.product'])->orderBy('tanggal', 'desc')->orderBy('id', 'desc');

        $now = Carbon::now();

        // Filter berdasarkan kolom 'tanggal' (karena tipe datanya 'date' di migration)
        switch ($periode) {
            case 'hari_ini':
                $query->whereDate('tanggal', Carbon::today());
                break;
            case 'minggu_ini':
                // Gunakan format Y-m-d agar cocok dengan kolom date
                $query->whereBetween('tanggal', [
                    $now->startOfWeek()->format('Y-m-d'),
                    $now->endOfWeek()->format('Y-m-d')
                ]);
                break;
            case 'bulan_ini':
                $query->whereMonth('tanggal', $now->month)
                      ->whereYear('tanggal', $now->year);
                break;
            case 'tahun_ini':
                $query->whereYear('tanggal', $now->year);
                break;
            case 'semua':
            default:
                break;
        }

        $restocks = $query->get();

        // Format data agar siap dikirim sebagai JSON
        return $restocks->map(function ($restock, $index) {
            return [
                'no' => $index + 1,
                'nomor_restock' => $restock->nomor_restock ?? '-',
                'supplier' => $restock->nama_supplier ?? 'Tanpa Supplier',
                'tanggal' => Carbon::parse($restock->tanggal)->format('d M Y'),
                'total_pengeluaran' => $restock->total_pengeluaran ?? 0,
                'details' => $restock->details->map(function ($detail) {
                    return [
                        'nama_produk' => $detail->product->nama_produk ?? '-',
                        'qty' => $detail->jumlah ?? 0,
                        'harga_beli' => $detail->harga_beli_satuan ?? 0,
                        'subtotal' => $detail->subtotal ?? 0,
                    ];
                })
            ];
        });
    }
}
