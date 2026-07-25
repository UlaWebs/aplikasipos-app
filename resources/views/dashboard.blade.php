<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard Operasional') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-indigo-500">
                    <div class="text-gray-500 text-xs uppercase font-bold">Omzet Hari Ini</div>
                    <div class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-2">
                        Rp {{ number_format($omzetHarian, 0, ',', '.') }}
                    </div>
                    <div class="text-xs text-green-600 mt-1">From {{ $transaksiHarian }} Transactions</div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                    <div class="text-gray-500 text-xs uppercase font-bold">Omzet Bulan Ini</div>
                    <div class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-2">
                        Rp {{ number_format($omzetBulanan, 0, ',', '.') }}
                    </div>
                    <div class="text-xs text-gray-400 mt-1">Terus tingkatkan! 🚀</div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="text-gray-500 text-xs uppercase font-bold">Total Jenis Produk</div>
                    <div class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-2">
                        {{ $totalProduk }} Item
                    </div>
                    <div class="text-xs text-gray-400 mt-1">Siap dijual</div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-red-500">
                    <div class="text-gray-500 text-xs uppercase font-bold">Perlu Restock</div>
                    <div class="text-2xl font-bold text-red-600 mt-2">
                        {{ $stokMenipis->count() + $produkHabis->count() }} Item
                    </div>
                    <div class="text-xs text-red-400 mt-1">Segera cek gudang!</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 flex justify-between items-center">
                        <h3 class="font-bold text-gray-700 dark:text-gray-200">🔥 Produk Terlaris (Top 5)</h3>
                    </div>
                    <div class="p-0">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th class="px-6 py-3">Nama Produk</th>
                                    <th class="px-6 py-3 text-right">Terjual</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($terlaris as $item)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <td class="px-6 py-3 font-medium text-gray-900 dark:text-white">
                                        {{ $item->product->nama_produk ?? 'Produk Dihapus' }}
                                        <div class="text-xs text-gray-400">{{ $item->product->kode_produk ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-3 text-right font-bold text-indigo-600">
                                        {{ $item->total_terjual }} {{ $item->product->satuan ?? 'Pcs' }}
                                    </td>
                                </tr>
                                @empty
                                <tr class="bg-white border-b">
                                    <td colspan="2" class="px-6 py-4 text-center italic">Belum ada transaksi.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-red-50 dark:bg-red-900 flex justify-between items-center">
                        <h3 class="font-bold text-red-700 dark:text-red-200">⚠️ Stok Menipis / Habis</h3>
                        <a href="{{ route('restocks.create') }}" class="text-xs bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700">Restock Sekarang</a>
                    </div>
                    <div class="p-0">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th class="px-6 py-3">Nama Produk</th>
                                    <th class="px-6 py-3 text-right">Sisa Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($produkHabis as $habis)
                                <tr class="bg-red-100 border-b dark:bg-gray-800 dark:border-gray-700">
                                    <td class="px-6 py-3 font-medium text-red-800">
                                        {{ $habis->nama_produk }}
                                        <span class="ml-2 text-xs bg-red-600 text-white px-1 rounded">HABIS</span>
                                    </td>
                                    <td class="px-6 py-3 text-right font-bold text-red-600">0</td>
                                </tr>
                                @endforeach

                                @foreach($stokMenipis as $tipis)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <td class="px-6 py-3 font-medium text-gray-900 dark:text-white">
                                        {{ $tipis->nama_produk }}
                                    </td>
                                    <td class="px-6 py-3 text-right font-bold text-orange-500">
                                        {{ $tipis->stok }}
                                    </td>
                                </tr>
                                @endforeach
                                
                                @if($produkHabis->isEmpty() && $stokMenipis->isEmpty())
                                <tr class="bg-white border-b">
                                    <td colspan="2" class="px-6 py-4 text-center text-green-600 font-bold">
                                        ✨ Semua stok aman!
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>