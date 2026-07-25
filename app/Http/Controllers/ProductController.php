<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockAdjustment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    // 1. Menampilkan Daftar Barang (Index)
    public function index()
    {
        $products = Product::latest()->get();
        return view('products.index', compact('products'));
    }

    // 2. Menampilkan Form Tambah Barang (Create)
    public function create()
    {
        return view('products.create');
    }

    // 3. Menyimpan Data (Store)
    public function store(Request $request)
    {
        // 1. Validasi Dasar
        $request->validate([
            'kode_produk' => 'required|unique:products',
            'nama_produk' => 'required',
            'kategori'    => 'required',
            'satuan'      => 'nullable|string',
            'harga'       => 'required|numeric', // Harga Jual Wajib
            
            // Validasi Bersyarat:
            // Kalau stok_awal diisi lebih dari 0, maka harga_beli_awal WAJIB diisi
            'stok_awal'       => 'nullable|integer|min:0',
            'harga_beli_awal' => 'nullable|required_if:stok_awal,>,0|numeric|min:0',
        ]);

        try {
            DB::transaction(function () use ($request) {
                
                // Tentukan Harga Beli untuk disimpan di Master Data
                // Kalau stok awal > 0, pakai harga inputan. Kalau stok 0, harga beli dianggap 0 (karena belum ada transaksi)
                $hargaBeliFix = ($request->stok_awal > 0) ? $request->harga_beli_awal : 0;

                // A. Simpan Data Produk
                $product = Product::create([
                    'kode_produk' => $request->kode_produk,
                    'nama_produk' => $request->nama_produk,
                    'kategori'    => $request->kategori,
                    'satuan'      => $request->satuan ?? 'pcs',
                    'harga'       => $request->harga,
                    'harga_beli'  => $hargaBeliFix, // Ini yang masuk ke DB
                    'stok'        => $request->stok_awal ?? 0, 
                ]);

                // B. Catat Stok Awal (Kalau ada)
                if ($request->has('stok_awal') && $request->stok_awal > 0) {
                    StockAdjustment::create([
                        'product_id' => $product->id,
                        'jumlah'     => $request->stok_awal,
                        'tipe'       => 'stok_awal',
                        'catatan'    => 'Stok bawaan (Opening Balance)',
                        'tanggal'    => now(),
                    ]);
                    // Note: Di development tingkat lanjut, kita bisa simpan nilai aset (jumlah * harga_beli_awal) ke jurnal modal.
                }
            });

            return redirect()->route('products.index')->with('success', 'Barang berhasil ditambahkan!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }
}