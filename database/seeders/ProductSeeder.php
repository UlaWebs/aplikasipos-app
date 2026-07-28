<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'kode_produk' => 'PRD-001',
                'nama_produk' => 'Kopi Arabica',
                'kategori' => 'Minuman',
                'harga_beli' => 12000,
                'harga' => 18000,
                'stok' => 50,
                'satuan' => 'pcs',
            ],
            [
                'kode_produk' => 'PRD-002',
                'nama_produk' => 'Teh Celup',
                'kategori' => 'Minuman',
                'harga_beli' => 8000,
                'harga' => 12000,
                'stok' => 40,
                'satuan' => 'pcs',
            ],
            [
                'kode_produk' => 'PRD-003',
                'nama_produk' => 'Snack Kacang',
                'kategori' => 'Makanan',
                'harga_beli' => 5000,
                'harga' => 8000,
                'stok' => 60,
                'satuan' => 'pcs',
            ],
            [
                'kode_produk' => 'PRD-004',
                'nama_produk' => 'Sabun Mandi',
                'kategori' => 'Perawatan',
                'harga_beli' => 10000,
                'harga' => 15000,
                'stok' => 30,
                'satuan' => 'pcs',
            ],
            [
                'kode_produk' => 'PRD-005',
                'nama_produk' => 'Shampo',
                'kategori' => 'Perawatan',
                'harga_beli' => 14000,
                'harga' => 20000,
                'stok' => 25,
                'satuan' => 'pcs',
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['kode_produk' => $product['kode_produk']],
                $product
            );
        }
    }
}
