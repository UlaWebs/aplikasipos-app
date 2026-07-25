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

                    <form action="{{ route('products.update', $product) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div>
                                <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Kode
                                    Produk</label>
                                <input type="text" name="kode_produk"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm"
                                    value="{{ old('kode_produk', $product->kode_produk) }}" required>
                            </div>

                            <div>
                                <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Nama
                                    Produk</label>
                                <input type="text" name="nama_produk"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm"
                                    value="{{ old('nama_produk', $product->nama_produk) }}"
                                    required>
                            </div>

                            <div>
                                <label
                                    class="block font-medium text-sm text-gray-700 dark:text-gray-300">Kategori</label>
                                <select name="kategori"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                                    <option value="Makanan" {{ old('kategori', $product->kategori) == 'Makanan' ? 'selected' : '' }}>Makanan</option>
                                    <option value="Minuman" {{ old('kategori', $product->kategori) == 'Minuman' ? 'selected' : '' }}>Minuman</option>
                                    <option value="Bahan Baku" {{ old('kategori', $product->kategori) == 'Bahan Baku' ? 'selected' : '' }}>Bahan Baku</option>
                                    <option value="Lainnya" {{ old('kategori', $product->kategori) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                            </div>

                            <div>
                                <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Satuan</label>
                                <select name="satuan"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                                    <option value="Pcs" {{ old('satuan', $product->satuan) == 'Pcs' ? 'selected' : '' }}>Pcs</option>
                                    <option value="Box" {{ old('satuan', $product->satuan) == 'Box' ? 'selected' : '' }}>Box</option>
                                    <option value="Kg" {{ old('satuan', $product->satuan) == 'Kg' ? 'selected' : '' }}>Kg</option>
                                    <option value="Pack" {{ old('satuan', $product->satuan) == 'Pack' ? 'selected' : '' }}>Pack</option>
                                    <option value="Unit" {{ old('satuan', $product->satuan) == 'Unit' ? 'selected' : '' }}>Unit</option>
                                </select>
                            </div>

                            <div class="md:col-span-2">
                                <label
                                    class="block font-medium text-sm text-green-600 dark:text-green-400 font-bold">Harga
                                    Jual (Rp)</label>
                                <input type="number" name="harga"
                                    class="mt-1 block w-full md:w-1/2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm"
                                    value="{{ old('harga', $product->harga) }}"
                                    required>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('products.index') }}"
                                class="text-sm underline mr-4 text-gray-600">Batal</a>
                            <button type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition">
                                Simpan Barang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
