<?php

namespace App\Http\Controllers;

use App\Models\Coa;
use Illuminate\Http\Request;

class CoaController extends Controller
{
    // Menampilkan daftar akun
    public function index()
    {
        // Urutkan berdasarkan kode akun biar rapi (111, 112, dst)
        $coas = Coa::orderBy('kode_akun', 'asc')->get();
        return view('coas.index', compact('coas'));
    }

    // Form tambah akun
    public function create()
    {
        return view('coas.create');
    }

    // Simpan akun baru
    public function store(Request $request)
    {
        $request->validate([
            // Format: 'unique:nama_tabel_di_database,nama_kolom'
            'kode_akun' => 'required|numeric|unique:coas,kode_akun',
            'nama_akun' => 'required|string|max:255',
            'header_akun' => 'required',
        ], [
            // Custom Pesan Error (Opsional, biar bahasa Indonesia)
            'kode_akun.unique' => 'Kode akun sudah terdaftar!',
        ]);

        Coa::create($request->all());

        return redirect()->route('coas.index')->with('success', 'Akun berhasil ditambahkan!');
    }

    public function edit($id)
    {
        // Cari data akun berdasarkan ID
        $coa = \App\Models\Coa::findOrFail($id);

        // Lempar ke view edit
        return view('coas.edit', compact('coa'));
    }

    public function update(Request $request, $id)
    {
        // 1. Validasi
        $request->validate([
            'kode_akun' => 'required|numeric|unique:coas,kode_akun,' . $id,
            'nama_akun' => 'required|string|max:255',
            'header_akun' => 'required', // <--- SEBELUMNYA 'tipe_akun', GANTI JADI INI
        ]);

        // 2. Ambil data
        $coa = \App\Models\Coa::findOrFail($id);

        // 3. Update
        $coa->update([
            'kode_akun'   => $request->kode_akun,
            'nama_akun'   => $request->nama_akun,
            'header_akun' => $request->header_akun, // <--- INI JUGA DISAMAKAN
        ]);

        // 4. Redirect
        return redirect()->route('coas.index')->with('success', 'Data berhasil diupdate!');
    }

    // Hapus akun
    public function destroy(Coa $coa)
    {
        $coa->delete();
        return redirect()->route('coas.index')->with('danger', 'Akun berhasil dihapus!');
    }
}
