<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Akun Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form action="{{ route('coas.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                                Kode Akun (No. Perkiraan)
                            </label>
                            <input type="text" name="kode_akun"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline dark:bg-gray-700 dark:text-white dark:border-gray-600"
                                placeholder="Contoh: 111, 501, 602" value="{{ old('kode_akun') }}">
                            @error('kode_akun')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-xs mt-1">Pastikan kode unik dan belum pernah dipakai.</p>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                                Nama Akun
                            </label>
                            <input type="text" name="nama_akun"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline dark:bg-gray-700 dark:text-white dark:border-gray-600"
                                placeholder="Contoh: Kas Kecil, Utang Bank, Biaya Listrik"
                                value="{{ old('nama_akun') }}">
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                                Header / Kategori Akun
                            </label>
                            <select name="header_akun"
                                class="shadow border rounded w-full py-2 px-3 text-gray-700 dark:bg-gray-700 dark:text-white dark:border-gray-600">
                                <option value="" disabled selected>-- Pilih Kategori --</option>
                                <option value="Aset">1 - Aset (Harta)</option>
                                <option value="Kewajiban">2 - Kewajiban (Utang)</option>
                                <option value="Ekuitas">3 - Ekuitas (Modal)</option>
                                <option value="Pendapatan">4 - Pendapatan</option>
                                <option value="Beban">5 - Beban (Biaya)</option>
                            </select>
                        </div>

                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('coas.index') }}"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Batal
                            </a>
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Simpan Akun
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
