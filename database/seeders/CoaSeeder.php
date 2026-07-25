<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoaSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan nama kolom sesuai migration kamu
        // Biasanya: id, kode_akun, nama_akun, header_akun (aset/kewajiban/dll)
        
        $akun = [
            // ASET (HARTA)
            ['kode_akun' => '111', 'nama_akun' => 'Kas Toko', 'header_akun' => '1-Aset'],
            ['kode_akun' => '112', 'nama_akun' => 'Persediaan Barang Dagang', 'header_akun' => '1-Aset'],
            ['kode_akun' => '121', 'nama_akun' => 'Peralatan Toko', 'header_akun' => '1-Aset'],

            // KEWAJIBAN (UTANG)
            ['kode_akun' => '211', 'nama_akun' => 'Utang Usaha', 'header_akun' => '2-Kewajiban'],

            // EKUITAS (MODAL)
            ['kode_akun' => '311', 'nama_akun' => 'Modal Pemilik', 'header_akun' => '3-Ekuitas'],
            ['kode_akun' => '312', 'nama_akun' => 'Prive Pemilik', 'header_akun' => '3-Ekuitas'],

            // PENDAPATAN
            ['kode_akun' => '411', 'nama_akun' => 'Pendapatan Penjualan', 'header_akun' => '4-Pendapatan'],

            // BEBAN (PENGELUARAN)
            ['kode_akun' => '511', 'nama_akun' => 'Harga Pokok Penjualan (HPP)', 'header_akun' => '5-Beban'],
            ['kode_akun' => '611', 'nama_akun' => 'Beban Listrik & Air', 'header_akun' => '6-Beban'],
            ['kode_akun' => '612', 'nama_akun' => 'Beban Gaji', 'header_akun' => '6-Beban'],
            ['kode_akun' => '613', 'nama_akun' => 'Beban Sewa', 'header_akun' => '6-Beban'],
        ];

        DB::table('coas')->insert($akun);
    }
}