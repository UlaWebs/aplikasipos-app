<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Form Detail Restock (Produk Masuk)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="p-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form action="{{ route('restocks.storedetail') }}" method="POST">
                        @csrf
                        <input type="hidden" name="restock_id" value="{{ $restock->id }}">

                        <div class="mb-4">
                            <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                                Pilih Produk
                            </label>
                            <select name="product_id"
                                class="shadow border rounded w-full py-2 px-3 text-gray-700 dark:bg-gray-700 dark:text-white dark:border-gray-600 select2">
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">
                                        {{ $product->kode_produk }} - {{ $product->nama_produk }} (Sisa:
                                        {{ $product->stok }} {{ $product->satuan }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex gap-4 mb-6">
                            <div class="w-1/2">
                                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                                    Jumlah Beli (Qty)
                                </label>
                                <input type="number" name="jumlah"
                                    class="shadow border rounded w-full py-2 px-3 text-gray-700 dark:bg-gray-700 dark:text-white"
                                    placeholder="Contoh: 100">
                            </div>
                            <div class="w-1/2">
                                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                                    Total Modal Keluar (Rp)
                                </label>
                                <input type="number" name="harga_beli_satuan"
                                    class="shadow border rounded w-full py-2 px-3 text-gray-700 dark:bg-gray-700 dark:text-white"
                                    placeholder="Harga beli per item">
                            </div>
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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Produk</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Harga Beli</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Qty</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Subtotal</th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($details as $p)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900 dark:text-gray-100">
                                            {{ $p->product?->nama_produk ?? 'Produk tidak tersedia' }}</div>
                                        <div class="text-sm text-gray-500">{{ $p->product?->kode_produk ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <div class="text-sm font-bold text-gray-900 dark:text-gray-100">
                                            {{ number_format($p->harga_beli_satuan, 0, ',', '.') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ $p->jumlah }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <div class="text-sm font-bold text-gray-900 dark:text-gray-100">
                                            {{ number_format($p->harga_beli_satuan * $p->jumlah, 0, ',', '.') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <form action="{{ route('restocks.destroydetail', $p->id) }}" method="GET"
                                            class="inline-block" onsubmit="return confirm('Yakin hapus data restock ini?')">
                                            @csrf
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-900">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        Belum ada data produk. Yuk tambah dulu!
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
