<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Jurnal;
use App\Models\Coa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    // 1. Tampilkan Halaman Kasir
    public function index()
    {
        // Ambil semua produk buat ditampilkan di katalog kasir
        $products = Product::where('stok', '>', 0)->get();
        return view('transactions.index', compact('products'));
    }

    // 2. Proses Simpan Transaksi (Checkout)
    public function store(Request $request)
    {
        // 1. VALIDASI
        $request->validate([
            'cart'  => 'required', // Jangan 'array', karena yang dikirim JSON String
            'bayar' => 'required|numeric',
            'total' => 'required|numeric',
        ]);

        // 2. DECODE JSON KE ARRAY
        // Ini kunci perbaikannya! Kita ubah text JSON jadi Array PHP
        $cart = json_decode($request->cart, true);

        // Validasi tambahan kalau decode gagal atau keranjang kosong
        if (!$cart || count($cart) < 1) {
            return back()->with('error', 'Keranjang belanja kosong!');
        }

        $totalBelanja = $request->total;
        $bayar = $request->bayar;
        $kembalian = $bayar - $totalBelanja;

        if ($bayar < $totalBelanja) {
            return back()->with('error', 'Uang pembayaran kurang!');
        }

        try {
            DB::beginTransaction();

            // A. SIMPAN HEADER TRANSAKSI
            $transaction = Transaction::create([
                'user_id' => Auth::id() ?? 1, // Fallback ke id 1 kalau belum login
                'no_transaksi' => 'TRX-' . time(),
                'tanggal_transaksi' => now(),
                'total_harga' => $totalBelanja,
                'bayar' => $bayar,
                'kembalian' => $kembalian,
            ]);

            // Variabel buat hitung Total Modal (HPP)
            $totalHPP = 0;

            // B. SIMPAN DETAIL ITEM & UPDATE STOK
            foreach ($cart as $item) {
                // Ambil data produk terbaru dari DB (buat cek stok & harga beli)
                $productDB = Product::find($item['id']);

                if (!$productDB) continue; // Skip kalau produk dihapus

                // Cek stok lagi biar aman
                if ($productDB->stok < $item['qty']) {
                    throw new \Exception("Stok " . $productDB->nama_produk . " tidak cukup!");
                }

                // 1. Simpan Item Transaksi
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id'     => $item['id'],
                    'jumlah'         => $item['qty'],
                    'harga_satuan'   => $item['harga'], // Harga Jual saat itu
                    'subtotal'       => $item['harga'] * $item['qty'],
                ]);

                // 2. Kurangi Stok
                $productDB->decrement('stok', $item['qty']);

                // 3. Hitung Modal Barang ini (Penting buat Jurnal HPP)
                // Kita ambil harga_beli dari master data produk
                $totalHPP += ($productDB->harga_beli * $item['qty']);
            }

            // C. JURNAL OTOMATIS (AKUNTANSI)
            // Kita butuh 4 Akun: Kas(111), Pendapatan(411), HPP(511), Persediaan(112)
            $akunKas        = Coa::where('kode_akun', '111')->first();
            $akunPendapatan = Coa::where('kode_akun', '411')->first();
            $akunHPP        = Coa::where('kode_akun', '511')->first();
            $akunPersediaan = Coa::where('kode_akun', '112')->first();

            // Cek kelengkapan akun COA
            if ($akunKas && $akunPendapatan && $akunHPP && $akunPersediaan) {
                
                // JURNAL 1: Penjualan (Uang Masuk)
                // Debit: Kas | Kredit: Pendapatan
                Jurnal::create([
                    'transaction_id' => $transaction->id,
                    'coa_id' => $akunKas->id,
                    'tanggal' => now(),
                    'keterangan' => 'Penjualan: ' . $transaction->no_transaksi,
                    'debit' => $totalBelanja,
                    'kredit' => 0,
                ]);
                Jurnal::create([
                    'transaction_id' => $transaction->id,
                    'coa_id' => $akunPendapatan->id,
                    'tanggal' => now(),
                    'keterangan' => 'Penjualan: ' . $transaction->no_transaksi,
                    'debit' => 0,
                    'kredit' => $totalBelanja,
                ]);

                // JURNAL 2: Pengakuan Beban Pokok (Barang Keluar)
                // Debit: HPP | Kredit: Persediaan
                // Ini supaya nilai persediaan di Neraca berkurang sesuai harga modal
                if ($totalHPP > 0) {
                    Jurnal::create([
                        'transaction_id' => $transaction->id,
                        'coa_id' => $akunHPP->id,
                        'tanggal' => now(),
                        'keterangan' => 'HPP Penjualan: ' . $transaction->no_transaksi,
                        'debit' => $totalHPP,
                        'kredit' => 0,
                    ]);
                    Jurnal::create([
                        'transaction_id' => $transaction->id,
                        'coa_id' => $akunPersediaan->id,
                        'tanggal' => now(),
                        'keterangan' => 'Brg Keluar: ' . $transaction->no_transaksi,
                        'debit' => 0,
                        'kredit' => $totalHPP,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('transactions.index')->with('success', 'Transaksi Berhasil! Kembalian: Rp ' . number_format($kembalian, 0, ',', '.'));

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }
}