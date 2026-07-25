<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Akun COA') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    {{-- Form Mulai Di Sini --}}
                    <form action="{{ route('coa.update', $coa->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Kode Akun --}}
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700">Kode Akun</label>
                            <input type="number" name="kode_akun" 
                                   class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" 
                                   value="{{ old('kode_akun', $coa->kode_akun) }}">
                            
                            @error('kode_akun')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Nama Akun --}}
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700">Nama Akun</label>
                            <input type="text" name="nama_akun" 
                                   class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" 
                                   value="{{ old('nama_akun', $coa->nama_akun) }}">
                        </div>
                        
                        {{-- Header Akun (Ganti sesuai nama kolom di DB kamu, misal: tipe_akun atau header_akun) --}}
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700">Header Akun</label>
                            <select name="header_akun" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                                <option value="1" {{ $coa->header_akun == '1' ? 'selected' : '' }}>1 - Aset</option>
                                <option value="2" {{ $coa->header_akun == '2' ? 'selected' : '' }}>2 - Kewajiban</option>
                                <option value="3" {{ $coa->header_akun == '3' ? 'selected' : '' }}>3 - Ekuitas</option>
                                <option value="4" {{ $coa->header_akun == '4' ? 'selected' : '' }}>4 - Pendapatan</option>
                                <option value="5" {{ $coa->header_akun == '5' ? 'selected' : '' }}>5 - Beban</option>
                            </select>
                        </div>

                        <div class="flex items-center gap-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Update Perubahan
                            </button>
                            
                            <a href="{{ route('coas.index') }}" class="text-sm text-gray-600 hover:text-gray-900 underline">
                                Batal
                            </a>
                        </div>
                    </form>
                    {{-- Form Selesai --}}

                </div>
            </div>
        </div>
    </div>
</x-app-layout>