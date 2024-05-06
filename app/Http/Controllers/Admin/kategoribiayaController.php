<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\akunBiaya;
use App\Models\kategoribiaya;
use App\Models\log;
use Illuminate\Http\Request;

class kategoribiayaController extends Controller
{
    
    public function index(Request $request)
    {
        $filterNamaKategori = $request->query('nama_kategori');
        $filterSaldoMin = $request->query('saldo_min');
        $filterSaldoMax = $request->query('saldo_max');
        $filterStatus = $request->query('status');

        $query = kategoribiaya::query();

        if ($filterNamaKategori) {
            $query->where('nama_kategori', 'like', '%' . $filterNamaKategori . '%');
        }

        if ($filterSaldoMin) {
            $query->where('jumlah', '>=', $filterSaldoMin);
        }

        if ($filterSaldoMax) {
            $query->where('jumlah', '<=', $filterSaldoMax);
        }

        if ($filterStatus) {
            $query->where('status', '=', $filterStatus);
        }

        $t_kategoribiaya = $query->get();

        
        $biaya = akunBiaya::all();
        $historyData = akunBiaya::with('kategori')->get();
        

        return view('admin.user.kategoribiaya.kategori-biaya', compact('t_kategoribiaya', 'biaya','historyData'));
    }

    
    public function create()
    {
        return view('admin.user.kategoribiaya.kategori-biaya-tambah');
    }

    
    public function store(Request $request)
    {

        $request->validate([
            'nama_kategori' => 'required|unique:kategori_biaya'
        ]);

        kategoribiaya::create([
            'nama_kategori' => $request -> nama_kategori,
            'status' => $request -> status
        ]);

        $note = 'Menambah Kategori Biaya Baru - ' . $request->nama_kategori;
        log::create([
            'id_user' => auth()->user()->id,
            'notes' => $note,
        ]);

        return redirect()->route('kategori-biaya')->with('status', 'Ketegori Biaya berhasil disimpan!');
    }

    
    public function show(string $id)
    {
        
    }

    
    public function edit(string $id)
    {
        $t_kategoribiaya = kategoribiaya::find($id);
        return view('admin.user.kategoribiaya.kategori-biaya-edit', compact('t_kategoribiaya'));
    }

    
    public function update(Request $request, string $id)
    {
        $t_kategoribiaya = kategoribiaya::find($id);

        if($request->nama_kategori == $t_kategoribiaya->nama_kategori){

            $hps = str_replace(['Rp.', '.', ','], '', $request->jumlah);
            $ganti = (int)$hps;
            $t_kategoribiaya->nama_kategori = $request -> nama_kategori;
            $t_kategoribiaya->jumlah = $ganti;
            $t_kategoribiaya->status = $request->status;
            $t_kategoribiaya->save();
        }else{
            $request->validate([
                'nama_kategori' => 'required|unique:kategori_biaya'
            ]);

            $hps = str_replace(['Rp.', '.', ','], '', $request->jumlah);
            $ganti = (int)$hps;
            $t_kategoribiaya->nama_kategori = $request -> nama_kategori;
            $t_kategoribiaya->jumlah = $ganti;
            $t_kategoribiaya->status = $request->status;
            $t_kategoribiaya->save();

        }

        $note = 'Memperbarui Kategori Biaya - ' . $request->nama_kategori;
        log::create([
            'id_user' => auth()->user()->id,
            'notes' => $note,
        ]);

        return redirect()->route('kategori-biaya')->with('status', 'Kategori Biaya berhasil diedit!');
    }

    
    public function destroy(string $id)
    {
        $t_kategoribiaya = kategoribiaya::findOrFail($id);
        $nama_kategori = $t_kategoribiaya->nama_kategori;
        $t_kategoribiaya->delete();

        $note = 'Menghapus Kategori Biaya - ' . $nama_kategori;
        log::create([
            'id_user' => auth()->user()->id,
            'notes' => $note,
        ]);

        return redirect()->back()->with('status', 'Kategori Biaya berhasil dihapus!');
    }
}
