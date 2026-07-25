<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Produk Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                            <strong class="font-bold">Periksa inputanmu:</strong>
                            <ul class="mt-2 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('products.store') }}" method="POST">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div>
                                <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Kode Produk</label>
                                <input type="text" name="kode_produk" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm" required>
                            </div>

                            <div>
                                <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Nama Produk</label>
                                <input type="text" name="nama_produk" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm" required>
                            </div>

                            <div>
                                <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Kategori</label>
                                <select name="kategori" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                                    <option value="Makanan">Makanan</option>
                                    <option value="Minuman">Minuman</option>
                                    <option value="Bahan Baku">Bahan Baku</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>

                            <div>
                                <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Satuan</label>
                                <select name="satuan" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                                    <option value="Pcs">Pcs</option>
                                    <option value="Box">Box</option>
                                    <option value="Kg">Kg</option>
                                    <option value="Pack">Pack</option>
                                    <option value="Unit">Unit</option>
                                </select>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block font-medium text-sm text-green-600 dark:text-green-400 font-bold">Harga Jual (Rp)</label>
                                <input type="number" name="harga" class="mt-1 block w-full md:w-1/2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm" required>
                            </div>

                            <div class="col-span-1 md:col-span-2 mt-4">
                                <div class="bg-gray-50 dark:bg-gray-700 p-5 rounded-lg border border-gray-200 dark:border-gray-600">
                                    <h3 class="text-md font-bold text-gray-800 dark:text-gray-200 mb-3">Stok Awal (Opsional)</h3>
                                    <p class="text-sm text-gray-500 mb-4">Isi bagian ini <b>HANYA JIKA</b> barang sudah tersedia fisiknya saat ini.</p>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jumlah Stok Fisik</label>
                                            <input type="number" name="stok_awal" value="0" min="0" class="mt-1 block w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-red-600 dark:text-red-400">Harga per Unit (Rp)</label>
                                            <input type="number" name="harga_beli_awal" value="0" min="0" class="mt-1 block w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm">
                                            <p class="text-xs text-gray-500 mt-1">Wajib diisi jika stok awal > 0.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('products.index') }}" class="text-sm underline mr-4 text-gray-600">Batal</a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition">
                                Simpan Barang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>