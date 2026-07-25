<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Laporan Laba Rugi (Income Statement)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg border-t-4 border-indigo-600">
                <div class="p-8 text-gray-900 dark:text-gray-100">

                    <div class="text-center mb-8">
                        <h3 class="text-2xl font-bold uppercase tracking-widest">Toko Grosir Sukses</h3>
                        <p class="text-gray-500 text-sm">Laporan Laba Rugi</p>
                        <p class="text-gray-400 text-xs">Periode: S.d. {{ now()->format('d M Y') }}</p>
                    </div>

                    <div class="mb-6">
                        <h4 class="font-bold text-lg border-b border-gray-300 pb-2 mb-3">PENDAPATAN</h4>
                        <div class="flex justify-between items-center mb-2">
                            <span>Penjualan Bersih</span>
                            <span class="font-mono">Rp {{ number_format($pendapatan, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center font-bold text-indigo-600 mt-2 bg-indigo-50 p-2 rounded">
                            <span>Total Pendapatan</span>
                            <span>Rp {{ number_format($pendapatan, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h4 class="font-bold text-lg border-b border-gray-300 pb-2 mb-3">HARGA POKOK PENJUALAN</h4>
                        <div class="flex justify-between items-center mb-2">
                            <span>HPP (Modal Barang)</span>
                            <span class="font-mono text-red-500">
                                (Rp {{ number_format($hpp, 0, ',', '.') }})
                            </span>
                        </div>

                        <div class="flex justify-between items-center font-bold text-gray-800 dark:text-white mt-4 pt-2 border-t border-dashed border-gray-400">
                            <span>LABA KOTOR (Gross Profit)</span>
                            <span>Rp {{ number_format($labaKotor, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h4 class="font-bold text-lg border-b border-gray-300 pb-2 mb-3">BEBAN OPERASIONAL</h4>
                        @forelse($bebanList as $beban)
                            <div class="flex justify-between items-center mb-1 text-sm">
                                <span>{{ $beban->nama_akun }}</span>
                                <span class="font-mono">Rp {{ number_format($beban->saldo_akhir, 0, ',', '.') }}</span>
                            </div>
                        @empty
                            <div class="text-gray-400 italic text-sm mb-2">- Tidak ada beban tercatat -</div>
                        @endforelse

                        <div class="flex justify-between items-center font-bold text-red-600 mt-2 bg-red-50 p-2 rounded">
                            <span>Total Beban</span>
                            <span>(Rp {{ number_format($totalBeban, 0, ',', '.') }})</span>
                        </div>
                    </div>

                    <div class="mt-8 pt-4 border-t-2 border-gray-800 dark:border-white">
                        <div class="flex justify-between items-center text-xl font-extrabold {{ $labaBersih >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            <span>LABA BERSIH (Net Profit)</span>
                            <span>Rp {{ number_format($labaBersih, 0, ',', '.') }}</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
