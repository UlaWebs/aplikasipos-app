<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Laporan Posisi Keuangan (Neraca)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <div class="text-center mb-6">
                        <h3 class="text-2xl font-bold uppercase">Neraca (Balance Sheet)</h3>
                        <p class="text-gray-500 text-sm">Per Tanggal: {{ now()->format('d M Y') }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 bg-gray-50 dark:bg-gray-700">
                            <h4 class="font-bold text-lg text-indigo-600 border-b border-gray-300 pb-2 mb-4">ASET (AKTIVA)</h4>
                            
                            @foreach($aset as $akun)
                                @if($akun->saldo != 0)
                                <div class="flex justify-between items-center mb-2 text-sm">
                                    <span>{{ $akun->kode_akun }} - {{ $akun->nama_akun }}</span>
                                    <span class="font-mono">Rp {{ number_format($akun->saldo, 0, ',', '.') }}</span>
                                </div>
                                @endif
                            @endforeach

                            <div class="mt-8 pt-4 border-t-2 border-indigo-600 flex justify-between items-center font-bold text-lg">
                                <span>TOTAL ASET</span>
                                <span>Rp {{ number_format($totalAset, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 bg-gray-50 dark:bg-gray-700">
                            <h4 class="font-bold text-lg text-red-600 border-b border-gray-300 pb-2 mb-4">KEWAJIBAN & EKUITAS</h4>
                            
                            <div class="mb-6">
                                <h5 class="font-bold text-sm text-gray-500 mb-2">KEWAJIBAN (UTANG)</h5>
                                @foreach($kewajiban as $akun)
                                    @if($akun->saldo != 0)
                                    <div class="flex justify-between items-center mb-2 text-sm">
                                        <span>{{ $akun->kode_akun }} - {{ $akun->nama_akun }}</span>
                                        <span class="font-mono">Rp {{ number_format($akun->saldo, 0, ',', '.') }}</span>
                                    </div>
                                    @endif
                                @endforeach
                            </div>

                            <div>
                                <h5 class="font-bold text-sm text-gray-500 mb-2">EKUITAS (MODAL)</h5>
                                @foreach($modal as $akun)
                                    @if($akun->saldo != 0)
                                    <div class="flex justify-between items-center mb-2 text-sm">
                                        <span>{{ $akun->kode_akun }} - {{ $akun->nama_akun }}</span>
                                        <span class="font-mono">Rp {{ number_format($akun->saldo, 0, ',', '.') }}</span>
                                    </div>
                                    @endif
                                @endforeach

                                <div class="flex justify-between items-center mb-2 text-sm text-green-600 font-bold bg-green-50 dark:bg-gray-900 p-2 rounded">
                                    <span>Laba Periode Berjalan</span>
                                    <span class="font-mono">Rp {{ number_format($labaBersih, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <div class="mt-8 pt-4 border-t-2 border-red-600 flex justify-between items-center font-bold text-lg">
                                <span>TOTAL PASIVA</span>
                                <span>Rp {{ number_format($totalPasiva, 0, ',', '.') }}</span>
                            </div>
                        </div>

                    </div>

                    @if($totalAset == $totalPasiva)
                        <div class="mt-6 p-4 bg-green-100 text-green-800 text-center rounded-lg font-bold border border-green-400">
                            ✅ BALANCE (SEIMBANG)
                        </div>
                    @else
                        <div class="mt-6 p-4 bg-red-100 text-red-800 text-center rounded-lg font-bold border border-red-400">
                            ❌ TIDAK BALANCE (Selisih: Rp {{ number_format($totalAset - $totalPasiva, 0, ',', '.') }})
                            <p class="text-xs font-normal mt-1">Cek kembali jurnal transaksi.</p>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>