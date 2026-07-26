<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Form Restock (Produk Masuk)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form action="{{ route('restocks.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="tanggal">Tanggal Pembelian</label>
                            <input type="date" name="tanggal" id="tanggal"
                                class="shadow border rounded w-full py-2 px-3 text-gray-700 dark:bg-gray-700 dark:text-white"
                                placeholder="Contoh: 100" value="{{ old('tanggal') }}">
                        </div>

                        <div class="mb-4">
                            <label for="nama_supplier">Nama Supplier</label>
                            <input type="text" name="nama_supplier" id="nama_supplier"
                                class="shadow border rounded w-full py-2 px-3 text-gray-700 dark:bg-gray-700 dark:text-white"
                                placeholder="Contoh: 100" value="{{ old('nama_supplier') }}">
                        </div>

                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('restocks.index') }}"
                                class="text-gray-500 hover:text-gray-700 font-bold py-2 px-4">Batal</a>
                            <button type="submit"
                                class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded shadow">
                                + Tambah Stok
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
