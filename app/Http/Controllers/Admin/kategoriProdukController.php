<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\kategoriProduk;
use App\Models\log;
use Illuminate\Http\Request;

class kategoriProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $t_kategori_produk = kategoriProduk::all();
        return view('admin.user.kategori_produk.produk-kategori', compact('t_kategori_produk'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.user.kategori_produk.produk-kategori-tambah');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_produk_kategori' => 'required'
        ]);
        kategoriProduk::create([
            'nama_produk_kategori' => $request->nama_produk_kategori
        ]);

        $note = 'Menambah Kategori Produk Baru - ' . $request->nama_produk_kategori;
        log::create([
            'id_user' => auth()->user()->id,
            'notes' => $note,
        ]);

        return redirect()->route('produk-kategori')->with('status', 'Kategori Produk berhasil disimpan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $prokat = kategoriProduk::find($id);
        return view('admin.user.kategori_produk.produk-kategori-edit', compact('prokat'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $prokat = kategoriProduk::find($id);
        $request->validate([
            'nama_produk_kategori' => 'required'
        ]);
        $prokat->nama_produk_kategori = $request->nama_produk_kategori;
        // dd($prokat);
        $prokat->save();

        $note = 'Memperbarui Kategori Produk - ' . $request->nama_produk_kategori;
        log::create([
            'id_user' => auth()->user()->id,
            'notes' => $note,
        ]);

        return redirect()->route('produk-kategori')->with('status', 'Kategori Produk berhasil diedit!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $prokat = kategoriProduk::findOrFail($id);
        $nama_produk_kategori = $prokat->nama_produk_kategori;
        $prokat->delete();

        $note = 'Menghapus Kategori Produk - ' . $nama_produk_kategori;
        log::create([
            'id_user' => auth()->user()->id,
            'notes' => $note,
        ]);

        return redirect()->back()->with('status', 'Kategori Produk berhasil dihapus!');
    }
}
