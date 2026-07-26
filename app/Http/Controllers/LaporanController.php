<?php

namespace App\Http\Controllers;

use App\Models\Jurnal;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function jurnalUmum()
    {
        // Ambil semua data jurnal, urutkan dari yang terbaru
        // Kita load 'coa' biar gak berat (Eager Loading)
        $jurnals = Jurnal::with(['coa', 'transaction'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        return view('laporan.jurnal_umum', compact('jurnals'));
    }

    public function bukuBesar(Request $request)
    {
        // 1. Ambil semua akun COA buat isi Dropdown Pilihan
        $coas = \App\Models\Coa::orderBy('kode_akun')->get();

        // 2. Siapkan variabel kosong
        $jurnals = [];
        $selectedCoa = null;
        $saldoAwal = 0; // Nanti bisa dikembangin kalau ada fitur periode bulanan

        // 3. Kalau User sudah memilih Akun (klik tombol Filter)
        if ($request->has('akun_id') && $request->akun_id != '') {
            $selectedCoa = \App\Models\Coa::find($request->akun_id);

            // Ambil jurnal KHUSUS akun yg dipilih aja
            if ($selectedCoa) {
                $jurnals = Jurnal::where('coa_id', $request->akun_id)
                    ->orderBy('tanggal', 'asc') // Urutkan dari yg terlama biar saldo runut
                    ->get();
            }
        }

        return view('laporan.buku_besar', compact('coas', 'jurnals', 'selectedCoa'));
    }

    public function labaRugi()
    {
        // 1. Hitung PENDAPATAN (Akun kepala 4)
        // Pendapatan saldo normalnya di KREDIT, jadi (Kredit - Debit)
        $pendapatan = Jurnal::whereHas('coa', function ($q) {
            $q->where('kode_akun', 'like', '4%');
        })->sum('kredit') - Jurnal::whereHas('coa', function ($q) {
            $q->where('kode_akun', 'like', '4%');
        })->sum('debit');

        // 2. Hitung HPP (Akun kepala 5)
        // Beban saldo normalnya di DEBIT, jadi (Debit - Kredit)
        $hpp = Jurnal::whereHas('coa', function ($q) {
            $q->where('kode_akun', 'like', '5%');
        })->sum('debit') - Jurnal::whereHas('coa', function ($q) {
            $q->where('kode_akun', 'like', '5%');
        })->sum('kredit');

        // 3. Hitung BEBAN OPERASIONAL (Akun kepala 6)
        // Ambil detail per akun biar bisa dilist (misal: Beban Listrik 50rb, Gaji 1jt)
        $bebanList = \App\Models\Coa::where('kode_akun', 'like', '6%')->get()->map(function ($akun) {
            $saldo = Jurnal::where('coa_id', $akun->id)->sum('debit') - Jurnal::where('coa_id', $akun->id)->sum('kredit');
            $akun->saldo_akhir = $saldo;
            return $akun;
        })->where('saldo_akhir', '>', 0); // Ambil yang ada nilainya aja

        $totalBeban = $bebanList->sum('saldo_akhir');

        // 4. Hitung Laba/Rugi
        $labaKotor = $pendapatan - $hpp;
        $labaBersih = $labaKotor - $totalBeban;

        return view('laporan.laba_rugi', compact('pendapatan', 'hpp', 'bebanList', 'totalBeban', 'labaKotor', 'labaBersih'));
    }

    public function neraca()
    {
        // 1. ASET (HARTA) - Kode depan 1
        // Saldo Normal: Debit - Kredit
        $aset = \App\Models\Coa::where('kode_akun', 'like', '1%')->get()->map(function ($akun) {
            $akun->saldo = \App\Models\Jurnal::where('coa_id', $akun->id)->sum('debit')
                - \App\Models\Jurnal::where('coa_id', $akun->id)->sum('kredit');
            return $akun;
        });
        $totalAset = $aset->sum('saldo');

        // 2. KEWAJIBAN (UTANG) - Kode depan 2
        // Saldo Normal: Kredit - Debit
        $kewajiban = \App\Models\Coa::where('kode_akun', 'like', '2%')->get()->map(function ($akun) {
            $akun->saldo = \App\Models\Jurnal::where('coa_id', $akun->id)->sum('kredit')
                - \App\Models\Jurnal::where('coa_id', $akun->id)->sum('debit');
            return $akun;
        });
        $totalKewajiban = $kewajiban->sum('saldo');

        // 3. MODAL (EKUITAS) - Kode depan 3
        // Saldo Normal: Kredit - Debit
        $modal = \App\Models\Coa::where('kode_akun', 'like', '3%')->get()->map(function ($akun) {
            $akun->saldo = \App\Models\Jurnal::where('coa_id', $akun->id)->sum('kredit')
                - \App\Models\Jurnal::where('coa_id', $akun->id)->sum('debit');
            return $akun;
        });
        $totalModal = $modal->sum('saldo');

        // 4. HITUNG LABA BERSIH (Jembatan Laba Rugi ke Neraca)
        // Kita hitung ulang laba bersih biar gak perlu query ke view lain
        $pendapatan = \App\Models\Jurnal::whereHas('coa', fn($q) => $q->where('kode_akun', 'like', '4%'))->sum('kredit')
            - \App\Models\Jurnal::whereHas('coa', fn($q) => $q->where('kode_akun', 'like', '4%'))->sum('debit');

        $hpp = \App\Models\Jurnal::whereHas('coa', fn($q) => $q->where('kode_akun', 'like', '5%'))->sum('debit')
            - \App\Models\Jurnal::whereHas('coa', fn($q) => $q->where('kode_akun', 'like', '5%'))->sum('kredit');

        $beban = \App\Models\Jurnal::whereHas('coa', fn($q) => $q->where('kode_akun', 'like', '6%'))->sum('debit')
            - \App\Models\Jurnal::whereHas('coa', fn($q) => $q->where('kode_akun', 'like', '6%'))->sum('kredit');

        $labaBersih = $pendapatan - $hpp - $beban;

        // TOTAL PASIVA (Kanan) = Utang + Modal + Laba
        $totalPasiva = $totalKewajiban + $totalModal + $labaBersih;

        return view('laporan.neraca', compact('aset', 'totalAset', 'kewajiban', 'totalKewajiban', 'modal', 'totalModal', 'labaBersih', 'totalPasiva'));
    }

    public function penjualan()
    {
        return view('laporan.penjualan');
    }

    public function viewdatalaporan_penjualan($periode)
    {
        $data_laporan = penjualan::viewlaporan($periode);

        return response()->json([
            'status' => 200,
            'data_laporan' => $data_laporan
        ]);
    }

    public function pembelian()
    {


        return view('laporan.pembelian', compact('pembelian'));
    }
}
