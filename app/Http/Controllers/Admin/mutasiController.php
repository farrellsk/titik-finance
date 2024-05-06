<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\kasbank;
use App\Models\log;
use App\Models\mutasi;
use App\Models\triggerSaldo;
use Carbon\Carbon;
use Illuminate\Http\Request;

class mutasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $t_mutasi = mutasi::all();
        return view('admin.user.mutasi.mutasi-view', compact('t_mutasi'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $mutKas = kasbank::all();
        return view('admin.user.mutasi.mutasi-tambah', compact('mutKas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'akun_asal' => 'required',
            'akun_tujuan' => 'required|different:akun_asal',
            'jumlah_mutasi' => 'required',
            'biaya_layanan' => 'required',
            'lampiran' => 'nullable|file|mimes:png,jpg,jpeg,doc,pdf,docx|max:2048',
        ], [
            'akun_tujuan.different' => 'Akun Asal dan Akun Tujuan harus berbeda.',
        ]);

        if ($request->akun_tujuan == $request->akun_asal) {
            return redirect()->back()->withInput()->withErrors(['akun_tujuan' => 'Akun Asal dan Akun Tujuan harus berbeda.']);
        }

        $akunAsal = kasbank::find($request->akun_asal);
        $akunTujuan = kasbank::find($request->akun_tujuan);

        $hps = str_replace(['Rp.', '.', ',',], '', $request->jumlah_mutasi);
        $jumlahMutasi = (int)$hps;


        $hps2 = str_replace(['Rp.', '.', ',',], '', $request->biaya_layanan);
        $biayaLayanan = (int)$hps2;

        // Mengurangi saldo akun asal
        $akunAsal->saldo_akhir = $akunAsal->saldo_akhir - $jumlahMutasi - $biayaLayanan;
        $akunAsal->save();

        // Menambah saldo akun tujuan
        $akunTujuan->saldo_akhir = $akunTujuan->saldo_akhir + $jumlahMutasi;
        $akunTujuan->save();

        $waktu = \Carbon\Carbon::parse($request->waktu)->format('Y-m-d') . ' ' . \Carbon\Carbon::now()->format('H:i:s');

        if ($request->hasFile('lampiran')) {
            $lampiranNya = time() . '_' . $request->file('lampiran')->getClientOriginalName();
            $request->file('lampiran')->move(public_path('image/lampiran'), $lampiranNya);
            $idmut = mutasi::create([
                'akun_asal' => $request->akun_asal,
                'akun_tujuan' => $request->akun_tujuan,
                'waktu' => $waktu,
                'jumlah_mutasi' => $jumlahMutasi,
                'biaya_layanan' => $biayaLayanan,
                'keterangan' => $request->keterangan,
                'lampiran' => $lampiranNya,
            ]);

            triggerSaldo::create([
                'id_item' => null,
                'id_transaksi' => null,
                'saldo_sebelumnya' => $request->akun_asal,
                'saldo_setelahnya' =>  $jumlahMutasi,
                'jenis_transaksi' => 'Mutasi',
                'id_mutasi' => $idmut->id
            ]);
        }

        $idmut = mutasi::create([
            'akun_asal' => $request->akun_asal,
            'akun_tujuan' => $request->akun_tujuan,
            'waktu' => $waktu,
            'jumlah_mutasi' => $jumlahMutasi,
            'biaya_layanan' => $biayaLayanan,
            'keterangan' => $request->keterangan,
            'lampiran' => null,
        ]);

        triggerSaldo::create([
            'id_item' => null,
            'id_transaksi' => null,
            'saldo_sebelumnya' => $akunAsal->saldo_akhir,
            'saldo_setelahnya' =>  $akunTujuan->saldo_akhir,
            'jenis_transaksi' => 'Mutasi',
            'id_mutasi' => $idmut->id
        ]);

        $kasAsal = kasbank::where('id_kas', $idmut->akun_asal)->select('nama_akun')->first();
        $kasTujuan = kasbank::where('id_kas', $idmut->akun_tujuan)->select('nama_akun')->first();

        $note = 'Menambah Mutasi - ' . $kasAsal->nama_akun . ' Ke ' . $kasTujuan->nama_akun;
        log::create([
            'id_user' => auth()->user()->id,
            'notes' => $note,
        ]);

        return redirect()->route('mutasi-view')->with('status', 'Mutasi berhasil ditambahkan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $mutasi = mutasi::findOrFail($id);

        $akunAsal = Kasbank::find($mutasi->akun_asal);
        $akunAsal->saldo_akhir += $mutasi->jumlah_mutasi + $mutasi->biaya_layanan;
        $akunAsal->save();

        // Mengurangi saldo akun tujuan jika transaksi transfer
        if ($mutasi->akun_asal !== $mutasi->akun_tujuan) {
            $akunTujuan = Kasbank::find($mutasi->akun_tujuan);
            $akunTujuan->saldo_akhir -= $mutasi->jumlah_mutasi;
            $akunTujuan->save();
        }

        // Menghapus transaksi
        $mutasi->delete();

        $note = 'Menghapus Mutasi Di - ' . $akunAsal->nama_akun;
        log::create([
            'id_user' => auth()->user()->id,
            'notes' => $note,
        ]);

        return redirect()->back()->with('status', 'Mutasi Berhasil Dihapus');
    }

    public function select2namaKB(Request $request)
    {
        $searchTerm = $request->term;

        $selectNamaKB = kasbank::where('id_kas', 'like', '%' .  $searchTerm)
            ->select('id_kas', 'nama_akun')
            ->get();


        return response()->json([$selectNamaKB]);
    }

    public function getSaldoAkun($id)
    {
        $kasbank = kasbank::find($id);

        if ($kasbank) {
            return response()->json([
                'success' => true,
                'data' => [
                    'saldo_akhir' => $kasbank->saldo_akhir
                ]
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Akun tidak ditemukan'
            ]);
        }
    }
}
