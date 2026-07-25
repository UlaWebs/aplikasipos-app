<nav
    class="w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 min-h-screen flex flex-col justify-between">

    <div>
        <div class="h-20 flex items-center justify-center border-b border-gray-200 dark:border-gray-700 bg-indigo-600">
            <a href="{{ route('dashboard') }}" class="flex flex-col items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span class="mt-1 text-sm font-bold text-white tracking-widest">TOKO GROSIR RUDI JAYA</span>
            </a>
        </div>

        <div class="flex-1 py-6 px-3 space-y-1">

            <a href="{{ route('dashboard') }}"
                class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-700 rounded-lg transition {{ request()->routeIs('dashboard') ? 'bg-indigo-100 dark:bg-gray-700 text-indigo-700 font-bold' : '' }}">
                <span class="ml-2">Dashboard</span>
            </a>

            @if (Auth::user()->role == 'admin')
                <div class="pt-4 pb-1 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Master Data
                </div>

                <a href="{{ route('products.index') }}"
                    class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-700 rounded-lg transition {{ request()->routeIs('products.*') ? 'bg-indigo-100 dark:bg-gray-700 text-indigo-700 font-bold' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-75" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <span class="ml-3">Data Produk</span>
                </a>


                <a href="{{ route('coas.index') }}"
                    class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-700 rounded-lg transition {{ request()->routeIs('coas.*') ? 'bg-indigo-100 dark:bg-gray-700 text-indigo-700 font-bold' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-75" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="ml-3">Akun (COA)</span>
                </a>
            @endif


            <div class="pt-4 pb-1 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                Transaksi
            </div>

            <a href="{{ route('transactions.index') }}"
                class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-700 rounded-lg transition {{ request()->routeIs('transactions.*') ? 'bg-indigo-100 dark:bg-gray-700 text-indigo-700 font-bold' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-75" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 7h6m0 36v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                <span class="ml-3">Mesin Kasir</span>
            </a>

            @if (Auth::user()->role == 'admin')
                <a href="{{ route('restocks.index') }}"
                    class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-700 rounded-lg transition {{ request()->routeIs('restocks.*') ? 'bg-indigo-100 dark:bg-gray-700 text-indigo-700 font-bold' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-75" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="ml-3">Restock Barang</span>
                </a>
            @endif

            @if (Auth::user()->role == 'admin')
            <div class="pt-4 pb-1 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Laporan
                </div>

                <a href="{{ route('laporan.jurnal') }}"
                    class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-700 rounded-lg transition {{ request()->routeIs('laporan.jurnal') ? 'bg-indigo-100 dark:bg-gray-700 text-indigo-700 dark:text-indigo-400' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-75" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="ml-3">Jurnal Umum</span>
                </a>
                <a href="{{ route('laporan.buku_besar') }}"
                    class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('laporan.buku_besar') ? 'bg-indigo-50 dark:bg-gray-700 text-indigo-600 dark:text-indigo-400' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-75" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <span class="ml-3">Buku Besar</span>
                </a>
                <a href="{{ route('laporan.neraca') }}"
                    class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('laporan.neraca') ? 'bg-indigo-50 dark:bg-gray-700 text-indigo-600 dark:text-indigo-400' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-75" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                    </svg>
                    <span class="ml-3">Neraca (Balance Sheet)</span>
                </a>
                <a href="{{ route('laporan.laba_rugi') }}"
                    class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('laporan.laba_rugi') ? 'bg-indigo-50 dark:bg-gray-700 text-indigo-600 dark:text-indigo-400' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-75" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                    <span class="ml-3">Laporan Laba Rugi</span>
                </a>
                <a href="{{ route('laporan.penjualan') }}"
                    class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('laporan.penjualan') ? 'bg-indigo-50 dark:bg-gray-700 text-indigo-600 dark:text-indigo-400' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-75" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                    </svg>
                    <span class="ml-3">Laporan Penjualan</span>
                </a>
                <a href="{{ route('laporan.pembelian') }}"
                    class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('laporan.pembelian') ? 'bg-indigo-50 dark:bg-gray-700 text-indigo-600 dark:text-indigo-400' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-75" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                    </svg>
                    <span class="ml-3">Laporan Pembelian</span>
                </a>
            @endif

        </div>
    </div>

    <div class="p-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
        <div class="flex items-center mb-4 px-2">
            <div class="bg-indigo-100 p-2 rounded-full mr-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <div class="overflow-hidden">
                <div class="font-bold text-sm text-gray-800 dark:text-gray-200 truncate">{{ Auth::user()->name }}
                </div>
                <div class="text-xs text-gray-500 truncate">{{ ucfirst(Auth::user()->role) }}</div>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full flex items-center justify-center px-4 py-2 bg-white border border-gray-300 hover:bg-gray-100 text-gray-700 rounded-lg transition text-sm shadow-sm">
                Log Out
            </button>
        </form>
    </div>
</nav>
