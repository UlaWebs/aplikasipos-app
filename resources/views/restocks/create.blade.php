<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Form Restock (Barang Masuk)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form action="{{ route('restocks.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                                Pilih Barang
                            </label>
                            <select name="product_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 dark:bg-gray-700 dark:text-white dark:border-gray-600 select2">
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">
                                        {{ $product->kode_produk }} - {{ $product->nama_produk }} (Sisa: {{ $product->stok }} {{ $product->satuan }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex gap-4 mb-6">
                            <div class="w-1/2">
                                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                                    Jumlah Beli (Qty)
                                </label>
                                <input type="number" name="jumlah" class="shadow border rounded w-full py-2 px-3 text-gray-700 dark:bg-gray-700 dark:text-white" placeholder="Contoh: 100">
                            </div>
                            <div class="w-1/2">
                                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                                    Total Modal Keluar (Rp)
                                </label>
                                <input type="number" name="total_bayar" class="shadow border rounded w-full py-2 px-3 text-gray-700 dark:bg-gray-700 dark:text-white" placeholder="Total harga beli dari supplier">
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('restocks.index') }}" class="text-gray-500 hover:text-gray-700 font-bold py-2 px-4">Batal</a>
                            <button type="submit" class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded shadow">
                                + Tambah Stok
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>