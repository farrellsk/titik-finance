<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\kategoriProduk;
use App\Models\log;
use Illuminate\Http\Request;
use App\Models\produk;


class produkController extends Controller
{

    public function index(Request $request)
    {
        $f_nama_produk=$request->query('nama_produk');

        $query = produk::with('kategori');
        if ($f_nama_produk){
            $query->where('nama_produk', 'like', '%'. $f_nama_produk . '%');
        }

        $t_produk = $query->get();

        return view('admin.user.produk.produk', compact('t_produk'));
    }


    public function create()
    {
        return view('admin.user.produk.produk-tambah');
    }


    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required',
            'unit' => 'required'
        ]);

        $hargaJualFormat = str_replace(['Rp.', ',', '.'], '', $request->harga_jual);
        $hargaBeliFormat = str_replace(['Rp.', ',', '.'], '', $request->harga_beli);

        produk::create([
            'nama_produk' => $request->nama_produk,
            'harga_jual' => (int) $hargaJualFormat,
            'harga_beli' => (int) $hargaBeliFormat,
            'kode_produk' => $request->kode_produk,
            'kategori_produk' => $request->kategori_produk,
            'total_stok' => $request->total_stok,
            'minimun_stok' => $request->minimun_stok,
            'unit' => $request->unit,
        ]);

        $note = 'Menambah Produk Baru - ' . $request->nama_produk;
        log::create([
            'id_user' => auth()->user()->id,
            'notes' => $note,
        ]);

        return redirect()->route('produk')->with('status', 'Produk berhasil ditambahkan');
    }

    public function edit($id)
    {
        $produk = produk::find($id);
        return view('admin.user.produk.produk-edit', compact('produk'));
    }


    public function update(Request $request, $id)
    {

        $produk= produk::find($id);

        $request->validate([
            'nama_produk' => 'required',
            'unit' => 'required'
        ]);

        $hargaJualFormat = str_replace(['Rp.', ',', '.'], '', $request->harga_jual);
        $hargaBeliFormat = str_replace(['Rp.', ',', '.'], '', $request->harga_beli);

        $produk->nama_produk = $request->nama_produk;
        $produk->harga_jual = (int) $hargaJualFormat;
        $produk->harga_beli = (int) $hargaBeliFormat;
        $produk->kode_produk = $request->kode_produk;
        $produk->kategori_produk = $request->kategori_produk;
        $produk->total_stok = $request->total_stok;
        $produk->minimun_stok = $request->minimun_stok;
        $produk->unit = $request->unit;
        $produk->save();

        $note = 'Memperbarui Produk - ' . $request->nama_produk;
        log::create([
            'id_user' => auth()->user()->id,
            'notes' => $note,
        ]);

        return redirect()->route('produk');
    }

    public function destroy($id)
    {
        $produk = produk::findOrFail($id);
        $nama_produk = $produk->nama_produk;
        $produk->delete();

        $note = 'Menghapus Produk - ' . $nama_produk;
        log::create([
            'id_user' => auth()->user()->id,
            'notes' => $note,
        ]);

        return redirect()->back();
    }

    public function select2katprod(Request $request)
    {
        $searchTerm = $request->term;

        $selecKatProd = kategoriProduk::where('id','like', '%' .  $searchTerm)
            ->select('id', 'nama_produk_kategori')
            ->get();

        return response()->json([$selecKatProd]);
    }
}
