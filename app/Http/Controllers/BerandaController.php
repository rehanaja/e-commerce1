<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use App\Models\Produk;

=======
>>>>>>> 0b30345 (test)
class BerandaController extends Controller
{
    public function berandaBackend()
    {
        return view('v_beranda.index', [
            'judul' => 'Halaman Beranda',
<<<<<<< HEAD
        ]);
    }

    public function index()
    {
        $produk = Produk::where('status', 1)->orderBy('updated_at', 'desc')->paginate(6);

        return view('v_beranda.index', [
            'judul' => 'Halaman beranda',
            'produk' => $produk,
=======
>>>>>>> 0b30345 (test)
        ]);
    }
}
