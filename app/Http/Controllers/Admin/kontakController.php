<?php

namespace App\Http\Controllers\Admin;

use App\Models\kontak;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\log;
use Illuminate\Support\Facades\Auth;

class kontakController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $fNamaKontak = $request->query('nama_kontak');
        $fNoTelp = $request->query('no_telp');
        $fTipeKontak = $request->query('tipe_kontak');

        $query = kontak::query();

        if ($fNamaKontak) {
            $query->where('nama_kontak', 'like', '%' . $fNamaKontak . '%');
        }

        if ($fNoTelp) {
            $query->where('no_telp', 'like', '%' . $fNoTelp . '%');
        }

        if ($fTipeKontak) {
            $query->where('tipe_kontak', 'like', '%' . $fTipeKontak . '%');
        }

        $t_kontak = $query->get();

        return view('admin.user.kontak.kontak', compact('t_kontak'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.user.kontak.kontak-tambah');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $lastKontak = kontak::latest('kode_kontak')->first();

        if ($lastKontak == null) {
            $nextId = 'K-001';
        } else {
            $prefix = substr($lastKontak->kode_kontak, 0, 2);
            $lastNumber = (int)substr($lastKontak->kode_kontak, 2);
            $nextNumber = $lastNumber + 1;

            $nextId = $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        }

        $user = Auth::id();

        $request->validate([
            'tipe_kontak' => 'required'
        ]);
        kontak::create([
            'kode_kontak' => $nextId,
            'id_user' => $user,
            'nama_kontak' => $request->nama_kontak,
            'tipe_kontak' => $request->tipe_kontak,
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat
        ]);

        $note = 'Menambah Kontak Baru - ' . $request->nama_kontak;
        log::create([
            'id_user' => auth()->user()->id,
            'notes' => $note,
        ]);
        
        return redirect()->route('users.kontak')->with('status', 'Kontak berhasil disimpan!');
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
        $kontak = kontak::find($id);
        return view('admin.user.kontak.kontak-edit', compact('kontak'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $kontak = kontak::find($id);

        if ($request->no_telp == $kontak->no_telp) {
            $kontak->nama_kontak = $request->nama_kontak;
            $kontak->tipe_kontak = $request->tipe_kontak;
            $kontak->no_telp = $request->no_telp;
            $kontak->alamat = $request->alamat;
            $kontak->save();
        } else {
            $request->validate([
                // 'no_telp' => 'required|unique:kontak',
                'tipe_kontak' => 'required'
            ]);
            $kontak->nama_kontak = $request->nama_kontak;
            $kontak->tipe_kontak = $request->tipe_kontak;
            $kontak->no_telp = $request->no_telp;
            $kontak->alamat = $request->alamat;
            $kontak->save();
        }

        $note = 'Memperbarui Kontak - ' . $request->nama_kontak;
        log::create([
            'id_user' => auth()->user()->id,
            'notes' => $note,
        ]);

        return redirect()->route('users.kontak')->with('status', 'Kontak berhasil diedit!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kontak = kontak::findOrFail($id);
        $nama_kontak = $kontak->nama_kontak;
        $kontak->delete();

        $note = 'Memperbarui Kontak - ' . $nama_kontak;
        log::create([
            'id_user' => auth()->user()->id,
            'notes' => $note,
        ]);

        return redirect()->back()->with('status', 'kontak berhasil dihapus!');
    }
}
