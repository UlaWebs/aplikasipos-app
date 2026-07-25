<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Buku Besar (General Ledger)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form action="{{ route('laporan.buku_besar') }}" method="GET" class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg flex gap-4 items-end">
                        <div class="w-full md:w-1/3">
                            <label class="block text-sm font-bold mb-2">Pilih Akun (COA):</label>
                            <select name="akun_id" class="w-full rounded border-gray-300 dark:bg-gray-900 dark:border-gray-600">
                                <option value="">-- Pilih Akun --</option>
                                @foreach($coas as $coa)
                                    <option value="{{ $coa->id }}" {{ request('akun_id') == $coa->id ? 'selected' : '' }}>
                                        {{ $coa->kode_akun }} - {{ $coa->nama_akun }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded shadow">
                            Tampilkan Data
                        </button>
                    </form>

                    @if($selectedCoa)
                        <div class="mb-4">
                            <h3 class="text-lg font-bold">Kartu Akun: <span class="text-indigo-500">{{ $selectedCoa->nama_akun }} ({{ $selectedCoa->kode_akun }})</span></h3>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left border border-gray-200">
                                <thead class="text-xs text-white uppercase bg-gray-800">
                                    <tr>
                                        <th class="px-4 py-3 border">Tanggal</th>
                                        <th class="px-4 py-3 border">Keterangan</th>
                                        <th class="px-4 py-3 border text-right">Debit</th>
                                        <th class="px-4 py-3 border text-right">Kredit</th>
                                        <th class="px-4 py-3 border text-right bg-gray-700">Saldo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php 
                                        $saldo = 0; 
                                        // Tentukan saldo normal (Aset/Beban bertambah di Debit, sisanya di Kredit)
                                        // Header Akun: 1=Aset, 5=Beban, 6=Beban
                                        $isDebitNormal = in_array(substr($selectedCoa->kode_akun, 0, 1), ['1', '5', '6']);
                                    @endphp

                                    @forelse($jurnals as $row)
                                        @php
                                            // LOGIKA SALDO BERJALAN
                                            if($isDebitNormal) {
                                                // Kalau Aset/Beban: Debit nambah, Kredit kurang
                                                $saldo = $saldo + $row->debit - $row->kredit;
                                            } else {
                                                // Kalau Utang/Modal/Pendapatan: Kredit nambah, Debit kurang
                                                $saldo = $saldo + $row->kredit - $row->debit;
                                            }
                                        @endphp
                                        <tr class="border-b hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-4 py-3">{{ \Carbon\Carbon::parse($row->tanggal)->format('d/m/Y') }}</td>
                                            <td class="px-4 py-3">{{ $row->keterangan }}</td>
                                            <td class="px-4 py-3 text-right">{{ $row->debit > 0 ? number_format($row->debit) : '-' }}</td>
                                            <td class="px-4 py-3 text-right">{{ $row->kredit > 0 ? number_format($row->kredit) : '-' }}</td>
                                            <td class="px-4 py-3 text-right font-bold bg-gray-50 dark:bg-gray-900">
                                                Rp {{ number_format($saldo) }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                                Tidak ada transaksi untuk akun ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-10 text-gray-500 border-2 border-dashed border-gray-300 rounded-lg">
                            <p>Silakan pilih akun di atas untuk melihat rincian Buku Besar.</p>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>