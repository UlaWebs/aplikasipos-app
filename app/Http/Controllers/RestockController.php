<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Restock;
use App\Models\RestockDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RestockController extends Controller
{
    public function index()
    {
        $riwayat = Restock::latest()->get();

        return view('restocks.index', compact('riwayat'));
    }

    public function create()
    {
        $products = Product::orderBy('nama_produk')->get();

        return view('restocks.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'nama_supplier' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $nomorRestock = $this->generateNomorRestock();
            $tglFormat = date('Y-m-d', strtotime($request->tanggal));

            $restock = Restock::create([
                'nomor_restock' => $nomorRestock,
                'tanggal' => $tglFormat,
                'total_pengeluaran' => 0,
                'nama_supplier' => $request->nama_supplier,
            ]);

            DB::commit();

            return redirect()->route('restocks.detail', ['id' => $restock->id])
                ->with('success', 'Data restock berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollback();

            return back()->withInput()->with('error', 'Gagal restock: ' . $e->getMessage());
        }
    }

    public function generateNomorRestock()
    {
        $date = date('dmy');
        $latestRestock = Restock::latest('tanggal')->latest('id')->first();

        if ($latestRestock) {
            $lastNo = (int) substr($latestRestock->nomor_restock, -3);
            $newNo = 'PM-' . $date . sprintf('%03d', $lastNo + 1);
        } else {
            $newNo = 'PM-' . $date . '001';
        }

        return $newNo;
    }

    public function detail($id)
    {
        $restock = Restock::findOrFail($id);
        $details = RestockDetail::where('restock_id', $id)
            ->with('product')
            ->get();
        $products = Product::orderBy('nama_produk')->get();

        return view('restocks.detail', compact('restock', 'details', 'products'));
    }

    public function create_detail($id)
    {
        return $this->detail($id);
    }

    public function storedetail(Request $request)
    {
        $validated = $request->validate([
            'restock_id' => 'required|exists:restocks,id',
            'product_id' => 'required|exists:products,id',
            'jumlah' => 'required|numeric|min:1',
            'harga_beli_satuan' => 'required|numeric|min:0',
        ]);

        $product = Product::findOrFail($request->product_id);
        $product->harga_beli = $request->harga_beli_satuan;
        $product->stok += $request->jumlah;
        $product->save();

        $subtotal = $request->jumlah * $request->harga_beli_satuan;

        $restock = Restock::findOrFail($request->restock_id);
        $restock->total_pengeluaran += $subtotal;
        $restock->save();

        RestockDetail::create([
            'restock_id' => $request->restock_id,
            'product_id' => $request->product_id,
            'jumlah' => $request->jumlah,
            'harga_beli_satuan' => $request->harga_beli_satuan,
            'subtotal' => $subtotal,
        ]);

        $akunKas = \App\Models\Coa::where('kode_akun', '111')->first();
        $akunPersediaan = \App\Models\Coa::where('kode_akun', '112')->first();

        if ($akunKas && $akunPersediaan) {
            // Ambil data restock untuk mengambil nomor restocknya
            $dataRestock = \App\Models\Restock::find($request->restock_id);
            $nomorRestock = $dataRestock ? $dataRestock->nomor_restock : 'Baru';

            // 1. Persediaan Bertambah (Debit)
            \App\Models\Jurnal::create([
                'restock_id' => $request->restock_id,
                'transaction_id' => null, // Biarkan kosong karena ini bukan penjualan
                'coa_id' => $akunPersediaan->id,
                'tanggal' => now(),
                'keterangan' => 'Restock Masuk: ' . $nomorRestock,
                'debit' => $subtotal,
                'kredit' => 0,
            ]);

            // 2. Kas Berkurang (Kredit)
            \App\Models\Jurnal::create([
                'restock_id' => $request->restock_id,
                'transaction_id' => null, // Biarkan kosong
                'coa_id' => $akunKas->id,
                'tanggal' => now(),
                'keterangan' => 'Pengeluaran Restock: ' . $nomorRestock,
                'debit' => 0,
                'kredit' => $subtotal,
            ]);
        }

        return redirect()->route('restocks.detail', ['id' => $request->restock_id])
            ->with('success', 'Data barang restock berhasil ditambahkan');
    }

    public function edit_detail($id)
    {
        return $this->detail($id);
    }

    public function update_detail(Request $request, $id)
    {
        $request->merge(['restock_id' => $id]);

        return $this->storedetail($request);
    }

    public function destroydetail($id)
    {
        $detailRestock = RestockDetail::findOrFail($id);
        $restockId = $detailRestock->restock_id;
        $product = Product::findOrFail($detailRestock->product_id);
        $restock = Restock::findOrFail($restockId);

        $product->stok -= $detailRestock->jumlah;
        $product->save();

        $restock->total_pengeluaran -= $detailRestock->subtotal;
        $restock->save();

        $detailRestock->delete();

        return redirect()->route('restocks.detail', ['id' => $restockId])
            ->with('success', 'Data detail restock berhasil dihapus');
    }

    public function destroy($id)
    {
        // Cari data restock berdasarkan id_restock
        $restock = Restock::findOrFail($id);

        // Cek apakah restock memiliki detail restock
        $detailRestock = RestockDetail::where('id', $id)->count();

        if ($detailRestock > 0) {
            // Jika ada detail restock, kembalikan pesan error
            return redirect()->route('restocks.index')->with('error', 'Tidak bisa menghapus restock yang memiliki detail
            restock.');
        }

        // Jika tidak ada detail restock, lanjutkan penghapusan
        $restock->delete();

        return redirect()->route('restocks.index')->with('success', 'Data Berhasil di Hapus');
    }
}
