<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Laporan Penjualan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="text-center mb-8">
                        <h3 class="text-2xl font-bold uppercase tracking-widest">Toko Grosir Sukses</h3>
                        <p class="text-gray-500 text-sm">Laporan Penjualan</p>
                        <p class="text-gray-400 text-xs">Periode: S.d. {{ now()->format('d M Y') }}</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 border border-gray-200">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th class="px-6 py-3 border">Produk</th>
                                    <th class="px-6 py-3 border">Kategori</th>
                                    <th class="px-6 py-3 border">Total Unit</th>
                                    <th class="px-6 py-3 border text-right">Total Penjualan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($jurnals as $jurnal)
                                    <tr
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            Produk 1
                                        </td>
                                        <td class="px-6 py-4 font-mono text-xs">
                                            Kategori 1
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="font-bold text-indigo-600">
                                                12
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right font-mono">
                                            10000
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="font-bold bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white">
                                <tr>
                                    <td colspan="3" class="px-6 py-3 text-center">TOTAL BALANCE</td>
                                    <td class="px-6 py-3 text-right text-green-600">
                                        Rp {{ number_format($jurnals->sum('debit'), 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
