<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
public function index()
    {
        $user = \Illuminate\Support\Facades\Auth::user();

        if (strtolower($user->role) === 'pegawai') {
            
            // ... logika dashboard kasir (sama kayak tadi) ...
            $riwayatTransaksi = \App\Models\Transaction::where('user_id', $user->id)
                                ->whereDate('created_at', \Carbon\Carbon::today())
                                ->orderBy('created_at', 'desc')
                                ->get();
            
            $totalSetoran = $riwayatTransaksi->sum('total_harga');

            return view('dashboard_kasir', compact('riwayatTransaksi', 'totalSetoran'));
        }

        // ... logika dashboard admin di bawahnya ...


        // === SKENARIO 2: KALAU YANG LOGIN ADMIN (Logic Lama) ===
        // Biarkan logika dashboard admin yang lama tetap jalan di sini
        
        $hariIni = \Carbon\Carbon::today();
        $bulanIni = \Carbon\Carbon::now()->month;

        $omzetHarian = \App\Models\Transaction::whereDate('created_at', $hariIni)->sum('total_harga');
        $transaksiHarian = \App\Models\Transaction::whereDate('created_at', $hariIni)->count();
        
        $omzetBulanan = \App\Models\Transaction::whereMonth('created_at', $bulanIni)->sum('total_harga');
        $totalProduk = \App\Models\Product::count();

        // Produk Terlaris
        $terlaris = \App\Models\TransactionItem::select('product_id', \Illuminate\Support\Facades\DB::raw('SUM(jumlah) as total_terjual'))
                    ->with('product')
                    ->groupBy('product_id')
                    ->orderByDesc('total_terjual')
                    ->take(5)
                    ->get();

        // Stok Menipis & Habis
        $stokMenipis = \App\Models\Product::where('stok', '<=', 5)->where('stok', '>', 0)->get();
        $produkHabis = \App\Models\Product::where('stok', 0)->get();

        // Return view dashboard admin (yang lama)
        return view('dashboard', compact(
            'omzetHarian', 'transaksiHarian', 'omzetBulanan', 'totalProduk',
            'terlaris', 'stokMenipis', 'produkHabis'
        ));
    }
}