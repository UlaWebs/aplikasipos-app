<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Kasir Toko Grosir') }}
        </h2>
    </x-slot>

    <div class="py-6" x-data="cashierApp()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-4 bg-green-500 text-white px-4 py-3 rounded shadow-lg">
                    <strong class="font-bold">SUKSES!</strong> {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 bg-red-500 text-white px-4 py-3 rounded shadow-lg">
                    {{ session('error') }}
                </div>
            @endif

            <div class="flex flex-col md:flex-row gap-4 h-[80vh]">
                
                <div class="w-full md:w-2/3 flex flex-col">
                    <div class="mb-4">
                        <input x-model="search" type="text" placeholder="Cari nama barang atau kode..." 
                            class="w-full p-4 text-lg border-2 border-indigo-200 rounded-xl focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white">
                    </div>

                    <div class="flex-1 overflow-y-auto bg-white dark:bg-gray-800 rounded-xl shadow p-4">
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @foreach($products as $product)
                            <div @click="addToCart({{ $product }})" 
                                x-show="matchSearch('{{ strtolower($product->nama_produk) }}', '{{ strtolower($product->kode_produk) }}')"
                                class="cursor-pointer border border-gray-200 hover:border-indigo-500 hover:shadow-lg transition rounded-lg p-3 bg-gray-50 dark:bg-gray-700 dark:border-gray-600 group">
                                
                                <div class="h-24 bg-gray-200 rounded mb-2 overflow-hidden relative">
                                    @if($product->gambar)
                                        <img src="{{ asset('storage/' . $product->gambar) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                                    @else
                                        <div class="flex items-center justify-center h-full text-gray-400 text-xs">No Img</div>
                                    @endif
                                    <span class="absolute top-0 right-0 bg-black bg-opacity-50 text-white text-xs px-1 rounded-bl">
                                        {{ $product->stok }} {{ $product->satuan }}
                                    </span>
                                </div>
                                
                                <h3 class="font-bold text-gray-800 dark:text-gray-200 text-sm truncate">{{ $product->nama_produk }}</h3>
                                <p class="text-indigo-600 font-bold">Rp {{ number_format($product->harga, 0, ',', '.') }}</p>
                                <p class="text-xs text-gray-500">{{ $product->kode_produk }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="w-full md:w-1/3 flex flex-col bg-white dark:bg-gray-800 rounded-xl shadow h-full border-l-4 border-indigo-500">
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-indigo-50 rounded-t-xl">
                        <h3 class="text-lg font-bold text-gray-800">Keranjang Belanja</h3>
                        <p class="text-sm text-gray-500">Total Item: <span x-text="cart.length"></span></p>
                    </div>

                    <div class="flex-1 overflow-y-auto p-4 space-y-3">
                        <template x-if="cart.length === 0">
                            <div class="text-center text-gray-400 mt-10">
                                Keranjang Kosong.<br>Klik barang di kiri buat nambah.
                            </div>
                        </template>

                        <template x-for="(item, index) in cart" :key="item.id">
                            <div class="flex justify-between items-center bg-gray-50 dark:bg-gray-700 p-2 rounded">
                                <div class="flex-1">
                                    <div class="font-bold text-sm text-gray-800 dark:text-gray-200" x-text="item.nama_produk"></div>
                                    <div class="text-xs text-gray-500">
                                        Rp <span x-text="formatRupiah(item.harga)"></span> x 
                                        <span x-text="item.qty"></span> <span x-text="item.satuan"></span>
                                    </div>
                                </div>
                                
                                <div class="flex items-center gap-2">
                                    <button @click="updateQty(index, -1)" class="w-6 h-6 bg-red-100 text-red-600 rounded hover:bg-red-200">-</button>
                                    <span class="font-bold w-4 text-center text-sm" x-text="item.qty"></span>
                                    <button @click="updateQty(index, 1)" class="w-6 h-6 bg-green-100 text-green-600 rounded hover:bg-green-200">+</button>
                                </div>
                                
                                <button @click="removeItem(index)" class="ml-2 text-gray-400 hover:text-red-500">
                                    &times;
                                </button>
                            </div>
                        </template>
                    </div>

                    <div class="p-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 rounded-b-xl">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-600">Total Tagihan:</span>
                            <span class="text-2xl font-bold text-indigo-700">Rp <span x-text="formatRupiah(grandTotal())"></span></span>
                        </div>

                        <form action="{{ route('transactions.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="cart" :value="JSON.stringify(cart)">
                            <input type="hidden" name="total" :value="grandTotal()">

                            <div class="mb-3">
                                <label class="block text-xs font-bold text-gray-500 uppercase">Uang Pembayaran</label>
                                <input type="number" name="bayar" x-model="bayar" required
                                    class="w-full p-2 text-right font-bold text-lg border rounded focus:ring-indigo-500" placeholder="0">
                            </div>

                            <div class="flex justify-between items-center mb-4 text-sm">
                                <span>Kembalian:</span>
                                <span class="font-bold" :class="kembalian() < 0 ? 'text-red-500' : 'text-green-600'">
                                    Rp <span x-text="formatRupiah(kembalian())"></span>
                                </span>
                            </div>

                            <button type="submit" 
                                :disabled="cart.length === 0 || bayar < grandTotal()"
                                class="w-full py-3 rounded-lg text-white font-bold transition shadow-lg"
                                :class="cart.length === 0 || bayar < grandTotal() ? 'bg-gray-400 cursor-not-allowed' : 'bg-indigo-600 hover:bg-indigo-700'">
                                BAYAR SEKARANG
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function cashierApp() {
            return {
                search: '',
                cart: [],
                bayar: 0,

                // Fungsi Tambah ke Keranjang
                addToCart(product) {
                    // Cek stok dulu
                    if(product.stok <= 0) {
                        alert('Stok Habis!');
                        return;
                    }

                    // Cek apakah barang udah ada di keranjang?
                    let existingItem = this.cart.find(item => item.id === product.id);

                    if (existingItem) {
                        if(existingItem.qty < product.stok) {
                            existingItem.qty++;
                        } else {
                            alert('Stok tidak cukup!');
                        }
                    } else {
                        this.cart.push({
                            id: product.id,
                            nama_produk: product.nama_produk,
                            harga: product.harga,
                            satuan: product.satuan,
                            stok_max: product.stok,
                            qty: 1
                        });
                    }
                },

                // Update Jumlah (Plus/Minus)
                updateQty(index, amount) {
                    let item = this.cart[index];
                    if (amount === 1 && item.qty >= item.stok_max) {
                        alert('Stok mentok boss!');
                        return;
                    }
                    item.qty += amount;
                    if (item.qty < 1) {
                        this.removeItem(index);
                    }
                },

                // Hapus Item
                removeItem(index) {
                    this.cart.splice(index, 1);
                },

                // Hitung Total Belanja
                grandTotal() {
                    return this.cart.reduce((total, item) => total + (item.harga * item.qty), 0);
                },

                // Hitung Kembalian
                kembalian() {
                    return this.bayar - this.grandTotal();
                },

                // Filter Pencarian
                matchSearch(nama, kode) {
                    let keyword = this.search.toLowerCase();
                    return nama.includes(keyword) || kode.includes(keyword);
                },

                // Format Angka ke Rupiah (Ribuan)
                formatRupiah(number) {
                    return new Intl.NumberFormat('id-ID').format(number);
                }
            }
        }
    </script>
</x-app-layout>