<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Jurnal;
use App\Models\Coa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RestockController extends Controller
{
    // Halaman Riwayat Restock (Nanti kita bikin view-nya sederhana aja)
    public function index()
    {
        // Kita ambil data jurnal yang terkait restock aja (keterangannya mengandung kata 'Restock')
        $riwayat = Jurnal::where('keterangan', 'like', '%Restock%')
                        ->where('debit', '>', 0) // Ambil sisi debitnya aja biar gak dobel
                        ->latest()
                        ->get();

        return view('restocks.index', compact('riwayat'));
    }

    // Form Belanja Stok
    public function create()
    {
        $products = Product::orderBy('nama_produk')->get();
        return view('restocks.create', compact('products'));
    }

    // Proses Simpan
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'jumlah' => 'required|numeric|min:1',
            'total_bayar' => 'required|numeric|min:0', // Total Modal
        ]);

        try {
            DB::beginTransaction();

            $product = Product::findOrFail($request->product_id);

            // --- INI BAGIAN BARUNYA ---
            // 1. Hitung harga satuan terbaru (Total Modal / Jumlah Barang)
            $hargaBeliBaru = $request->total_bayar / $request->jumlah;

            // 2. Update Stok (DITAMBAH)
            $product->stok += $request->jumlah;

            // 3. Update Harga Beli di Master Data (DIGANTI dengan harga baru)
            $product->harga_beli = $hargaBeliBaru;

            // 4. Simpan perubahan ke Master Data
            $product->save();
            // --------------------------

            // Jurnal Otomatis (Sama kayak kodinganmu sebelumnya)
            $akunPersediaan = Coa::where('kode_akun', '112')->first();
            $akunKas = Coa::where('kode_akun', '111')->first();

            if ($akunPersediaan && $akunKas) {
                // Debit: Persediaan (Bertambah senilai total belanja)
                Jurnal::create([
                    'coa_id' => $akunPersediaan->id,
                    'tanggal' => now(),
                    'keterangan' => 'Restock: ' . $product->nama_produk,
                    'debit' => $request->total_bayar,
                    'kredit' => 0,
                ]);

                // Kredit: Kas (Berkurang senilai total belanja)
                Jurnal::create([
                    'coa_id' => $akunKas->id,
                    'tanggal' => now(),
                    'keterangan' => 'Restock: ' . $product->nama_produk,
                    'debit' => 0,
                    'kredit' => $request->total_bayar,
                ]);
            }

            DB::commit();
            return redirect()->route('restocks.index')->with('success', 'Stok nambah & Harga Beli di Master Data sudah diupdate!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal restock: ' . $e->getMessage());
        }
    }
}
