<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard Kasir') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-indigo-600 rounded-lg shadow-lg p-6 mb-6 text-white flex justify-between items-center">
                <div>
                    <h3 class="text-2xl font-bold">Halo, {{ Auth::user()->name }}! 👋</h3>
                    <p class="opacity-80">Selamat bertugas. Semangat melayani pelanggan!</p>
                </div>
                <div class="text-right">
                    <p class="text-sm opacity-80">Setoran Anda Hari Ini</p>
                    <p class="text-3xl font-bold">Rp {{ number_format($totalSetoran, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                    <h3 class="font-bold text-gray-700 dark:text-gray-200">📋 Log Transaksi Hari Ini ({{ date('d-m-Y') }})</h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="px-6 py-3">Jam</th>
                                <th class="px-6 py-3">No. Transaksi</th>
                                <th class="px-6 py-3 text-right">Total Belanja</th>
                                <th class="px-6 py-3 text-right">Bayar</th>
                                <th class="px-6 py-3 text-right">Kembalian</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($riwayatTransaksi as $trx)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50">
                                <td class="px-6 py-3">{{ $trx->created_at->format('H:i') }}</td>
                                <td class="px-6 py-3 font-mono text-indigo-600">{{ $trx->no_transaksi }}</td>
                                <td class="px-6 py-3 text-right font-bold text-gray-900 dark:text-white">
                                    Rp {{ number_format($trx->total_harga, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-3 text-right">
                                    Rp {{ number_format($trx->bayar, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-3 text-right text-green-600">
                                    Rp {{ number_format($trx->kembalian, 0, ',', '.') }}
                                </td>
                            </tr>
                            @empty
                            <tr class="bg-white border-b">
                                <td colspan="5" class="px-6 py-8 text-center text-gray-400 italic">
                                    Belum ada transaksi hari ini. Yuk mulai jualan!
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6 text-center">
                <a href="{{ route('transactions.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Buka Mesin Kasir Sekarang &rarr;
                </a>
            </div>

        </div>
    </div>
</x-app-layout>