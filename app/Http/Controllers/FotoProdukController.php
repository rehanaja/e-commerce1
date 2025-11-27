<?php

namespace App\Http\Controllers;

use App\Helpers\imageHelper;
use App\Models\FotoProduk;
use Illuminate\Http\Request;

class FotoProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
        'produk_id' => 'required|exists:produk,id',
        'foto_produk.*' => 'image|mimes:jpeg,jpg,png,gif|file|max:1024',
        ]);

        if ($request->hasFile('foto_produk')) {
        foreach ($request->file('foto_produk') as $file) {
        // Buat nama file yang unik
        $extension = $file->getClientOriginalExtension();
        $filename = date('YmdHis') . '_' . uniqid() . '.' . $extension;
        $directory = 'storage/img-produk/';
        // Simpan dan resize gambar menggunakan ImageHelper
        ImageHelper::uploadAndResize($file, $directory, $filename, 800, null);
        // Simpan data ke database
        FotoProduk::create([
        'produk_id' => $request->produk_id,
        'foto' => $filename,
        ]);
        }
        }
        return redirect()->route('backend.produk.show', $request->produk_id)
        ->with('success', 'Foto berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $foto = FotoProduk::findOrFail($id);
        $produkId = $foto->produk_id;
        // Hapus file gambar dari storage
        $imagePath = public_path('storage/img-produk/') . $foto->foto;
        if (file_exists($imagePath)) {
        unlink($imagePath);
        }
        // Hapus record dari database
        $foto->delete();
        return redirect()->route('backend.produk.show', $produkId)->with('success', 'Foto berhasil dihapus.');
    }
}
