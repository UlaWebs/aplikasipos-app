<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Laporan Pembelian (Restock)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="text-center mb-8">
                        <h3 class="text-2xl font-bold uppercase tracking-widest">Toko Grosir Sukses</h3>
                        <p class="text-gray-500 text-sm">Laporan Pembelian Barang</p>
                        <p class="text-gray-400 text-xs">Tanggal Cetak: {{ now()->format('d M Y H:i') }}</p>
                    </div>

                    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                        <div class="flex items-center space-x-2">
                            <label for="filter_periode" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                Periode:
                            </label>
                            <select id="filter_periode" onchange="loadLaporanPembelian(this.value)"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                                <option value="hari_ini" selected>Hari Ini</option>
                                <option value="minggu_ini">Minggu Ini</option>
                                <option value="bulan_ini">Bulan Ini</option>
                                <option value="tahun_ini">Tahun Ini</option>
                                <option value="semua">Semua Transaksi</option>
                            </select>
                        </div>

                        <div class="flex space-x-2">
                            <button onclick="window.print()" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-sm font-medium transition">
                                <i class="fas fa-print mr-1"></i> Cetak Laporan
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-300">
                                <tr>
                                    <th class="px-4 py-3 text-center w-12">No</th>
                                    <th class="px-6 py-3">No Restock</th>
                                    <th class="px-6 py-3">Supplier</th>
                                    <th class="px-6 py-3 text-center">Tanggal</th>
                                    <th class="px-6 py-3 text-right">Total Belanja</th>
                                    <th class="px-4 py-3 text-center w-16">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="table-body-pembelian">
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-400">
                                        Memuat data laporan...
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot class="font-bold bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-right uppercase tracking-wider">
                                        Total Pembelian Periode Ini
                                    </td>
                                    <td id="total-semua-pembelian" class="px-6 py-4 text-right text-red-600 dark:text-red-400 text-base font-mono">
                                        Rp 0
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            loadLaporanPembelian('hari_ini');
        });

        function loadLaporanPembelian(periode) {
            const tbody = document.getElementById('table-body-pembelian');
            const totalElement = document.getElementById('total-semua-pembelian');

            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-400">
                        <svg class="animate-spin h-5 w-5 mx-auto mb-2 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Memuat data...
                    </td>
                </tr>`;

            fetch(`/laporan/pembelian/data/${periode}`)
                .then(response => response.json())
                .then(res => {
                    if (res.status === 200 && res.data_laporan.length > 0) {
                        let html = '';
                        let grandTotal = 0;

                        res.data_laporan.forEach((restock, index) => {
                            grandTotal += parseFloat(restock.total_pengeluaran);

                            html += `
                                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-750 transition">
                                    <td class="px-4 py-4 text-center font-medium">${restock.no}</td>
                                    <td class="px-6 py-4 font-mono font-semibold text-indigo-600 dark:text-indigo-400">${restock.nomor_restock}</td>
                                    <td class="px-6 py-4">${restock.supplier}</td>
                                    <td class="px-6 py-4 text-center text-xs font-mono">${restock.tanggal}</td>
                                    <td class="px-6 py-4 text-right font-mono font-semibold">
                                        Rp ${formatRupiah(restock.total_pengeluaran)}
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <button onclick="toggleDetail('detail-beli-${index}')" class="text-xs bg-indigo-100 hover:bg-indigo-200 text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300 dark:hover:bg-indigo-800 px-2.5 py-1.5 rounded transition">
                                            Detail
                                        </button>
                                    </td>
                                </tr>
                                <tr id="detail-beli-${index}" class="hidden bg-gray-50 dark:bg-gray-900/40 border-b dark:border-gray-700">
                                    <td colspan="6" class="p-4">
                                        <div class="ml-8 p-3 bg-white dark:bg-gray-800 rounded border border-gray-200 dark:border-gray-700 shadow-sm">
                                            <p class="text-xs font-bold uppercase text-gray-500 mb-2">Item Produk Yang Di-Restock:</p>
                                            <table class="w-full text-xs text-left text-gray-600 dark:text-gray-300">
                                                <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                                    <tr>
                                                        <th class="px-3 py-2">Nama Produk</th>
                                                        <th class="px-3 py-2 text-center">Qty</th>
                                                        <th class="px-3 py-2 text-right">Harga Beli Satuan</th>
                                                        <th class="px-3 py-2 text-right">Subtotal</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    ${restock.details.map(item => `
                                                        <tr class="border-b dark:border-gray-700">
                                                            <td class="px-3 py-1.5 font-medium">${item.nama_produk}</td>
                                                            <td class="px-3 py-1.5 text-center font-bold">${item.qty}</td>
                                                            <td class="px-3 py-1.5 text-right font-mono">Rp ${formatRupiah(item.harga_beli)}</td>
                                                            <td class="px-3 py-1.5 text-right font-mono">Rp ${formatRupiah(item.subtotal)}</td>
                                                        </tr>
                                                    `).join('')}
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                            `;
                        });

                        tbody.innerHTML = html;
                        totalElement.innerText = 'Rp ' + formatRupiah(grandTotal);
                    } else {
                        tbody.innerHTML = `
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-400">
                                    Tidak ada riwayat pembelian/restock untuk periode ini.
                                </td>
                            </tr>`;
                        totalElement.innerText = 'Rp 0';
                    }
                })
                .catch(err => {
                    console.error(err);
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-red-500">
                                Gagal memuat data laporan pembelian.
                            </td>
                        </tr>`;
                });
        }

        function toggleDetail(id) {
            const el = document.getElementById(id);
            if (el) {
                el.classList.toggle('hidden');
            }
        }

        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID').format(number);
        }
    </script>
</x-app-layout>
