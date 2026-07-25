<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Riwayat Pembelian Stok') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium">Log Pembelian Barang</h3>
                        <a href="{{ route('restocks.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition">
                            + Restock Baru
                        </a>
                    </div>

                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Keterangan</th>
                                <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Nominal (Modal)</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($riwayat as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $item->tanggal }}</td>
                                <td class="px-6 py-4 text-sm font-medium">{{ $item->keterangan }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-red-500 font-bold">
                                    - Rp {{ number_format($item->debit, 0, ',', '.') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-gray-500">Belum ada riwayat restock.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>