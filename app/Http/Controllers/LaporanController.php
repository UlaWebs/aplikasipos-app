<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function jurnalUmum()
    {
        $jurnals = Laporan::getJurnalUmum();

        return view('laporan.jurnal_umum', compact('jurnals'));
    }

    public function bukuBesar(Request $request)
    {
        $data = Laporan::getBukuBesar($request->input('akun_id'));

        return view('laporan.buku_besar', $data);
    }

    public function labaRugi()
    {
        $data = Laporan::getLabaRugi();

        return view('laporan.laba_rugi', $data);
    }

    public function neraca()
    {
        $data = Laporan::getNeraca();

        return view('laporan.neraca', $data);
    }

    public function penjualan()
    {
        return view('laporan.penjualan');
    }

    /**
     * Mengambil data laporan penjualan berdasarkan filter periode
     */
    public function viewdatalaporan_penjualan($periode = 'hari_ini')
    {
        $data_laporan = Laporan::getPenjualan($periode);

        return response()->json([
            'status' => 200,
            'data_laporan' => $data_laporan
        ]);
    }

    public function pembelian()
    {
        return view('laporan.pembelian');
    }

    /**
     * Mengambil data laporan pembelian berdasarkan filter periode
     */
    public function viewdatalaporan_pembelian($periode = 'hari_ini')
    {
        $data_laporan = Laporan::getPembelian($periode);

        return response()->json([
            'status' => 200,
            'data_laporan' => $data_laporan
        ]);
    }
}
