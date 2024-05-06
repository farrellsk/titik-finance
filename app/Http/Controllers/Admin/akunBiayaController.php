<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\akunBiaya;
use App\Models\detailkasbank;
use App\Models\kasbank;
use App\Models\kategoribiaya;
use App\Models\kontak;
use App\Models\log;
use App\Models\triggerBiaya;

class akunBiayaController extends Controller
{
    public function show(Request $request)
    {
        $fNamaBiaya = $request->query('nama_akun_biaya');
        $fMinBiaya = $request->query('jumlah_min_biaya');
        $fMaxBiaya = $request->query('jumlah_max_biaya');
        $fKategoriSelect = $request->query('kategori_akun_biaya');

        $query = akunBiaya::query();

        if ($fNamaBiaya) {
            $query->where('nama_akun_biaya', 'like', '%' . $fNamaBiaya . '%');
        }

        if ($fMinBiaya) {
            $query->where('jumlah', '>=', $fMinBiaya);
        }

        if ($fMaxBiaya) {
            $query->where('jumlah', '<=', $fMaxBiaya);
        }

        if ($fKategoriSelect) {
            $query->where('kategori_akun_biaya', '=', $fKategoriSelect);
        }

        $akunB = $query->get();

        return view('admin.user.biaya.akun-biaya', ['akunB' => $akunB]);
    }

    public function create()
    {
        $lastAkunB = akunBiaya::latest('kd_akun_biaya')->first();

        $metodePembayaran = kasbank::all();



        if ($lastAkunB) {

            $prefix = substr($lastAkunB->kd_akun_biaya, 0, 3);


            $lastNumber = (int)substr($lastAkunB->kd_akun_biaya, 3);
            $nextNumber = $lastNumber + 1;

            $nextId = $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        } else {
            $nextId = 'B-001';
        }

        return view('admin.user.biaya.buat-akunBiaya', ['nextId' => $nextId, 'metodePembayaran' => $metodePembayaran]);
    }

    public function insert(Request $request)
    {

        $lastAkunB = akunBiaya::latest('kd_akun_biaya')->first();

        if ($lastAkunB == null) {
            $nextId = 'B-001';
        } else {
            $prefix = substr($lastAkunB->kd_akun_biaya, 0, 2);
            $lastNumber = (int)substr($lastAkunB->kd_akun_biaya, 2);
            $nextNumber = $lastNumber + 1;

            $nextId = $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        }

        $hpsformat = str_replace(['Rp.', ',', '.'], '', $request->jumlah);
        $ubhformat = (int)$hpsformat;


        $slkasbank = kasbank::where('nama_akun', $request->payment)->first();

        if ($slkasbank && $ubhformat > $slkasbank->saldo_akhir) {
        return redirect()->route('akun-biaya')->withErrors(['Saldo Tidak Mencukupi!']);
        }

        $id_kat = kategoribiaya::select('id_kategori_biaya')
        ->where('nama_kategori', $request->nama_kategori)
        ->first();

       $biaya = akunBiaya::create([
            'id_kategori_biaya' => $id_kat->id_kategori_biaya,
            'kd_akun_biaya' => $nextId,
            'nama_akun_biaya' => $request->keterangan,
            'nama_penerima' => $request->nama_penerima,
            'kategori_akun_biaya' => $request->nama_kategori,
            'metode_pembayaran' => $request->payment,
            'jumlah' => $ubhformat,
        ]);

        $saldo_setelahnya = $slkasbank->saldo_akhir - $ubhformat;

        triggerBiaya::create([
            'id_tbiaya' => $biaya->kd_akun_biaya,
            'saldo_sebelumnya' => $slkasbank->saldo_akhir,
            'saldo_setelahnya' => $saldo_setelahnya,
            'waktu' => $biaya->created_at
        ]);

        $kategoriBiaya = kategoribiaya::where('nama_kategori', $request->nama_kategori)->first();
        if ($kategoriBiaya) {
            $kategoriBiaya->jumlah += $ubhformat;
            $kategoriBiaya->save();
        } else {
            //
        }

        $slkasbank = kasbank::where('nama_akun', $request->payment)->first();

        if ($slkasbank) {
            $saldo_akhir = $slkasbank->saldo_akhir;
            $saldo_akhir = max(0, $saldo_akhir - $ubhformat);
            
            // Simpan saldo akhir
            $slkasbank->saldo_akhir = $saldo_akhir;
            $slkasbank->save();
        }

        $note = 'Menambah Biaya Baru ' . $biaya->kd_akun_biaya;
        log::create([
            'id_user' => auth()->user()->id,
            'notes' => $note,
        ]);
        
        return redirect()->route('akun-biaya')->with('status', 'Biaya telah ditambahkan');
        }

    public function hapusAkb($id_akb)
    {
        $akunB = akunBiaya::find($id_akb);
        $jumlahB = $akunB->jumlah;

        $slkasbank = kasbank::where('nama_akun', $akunB->metode_pembayaran)->first();
        if($slkasbank)
        {
            $slkasbank->saldo_akhir += $jumlahB;
            $slkasbank->save();
        }
        
        $kategoriBiaya = kategoribiaya::where('nama_kategori', $akunB->kategori_akun_biaya)->first();

        if ($kategoriBiaya) {
            $kategoriBiaya->jumlah -= $jumlahB;
            $kategoriBiaya->save();
        } else {
        }

        $akunB->delete();

        $note = 'Menghapus Biaya ' . $akunB->kd_akun_biaya;
        log::create([
            'id_user' => auth()->user()->id,
            'notes' => $note,
        ]);

        return redirect()->route('akun-biaya')->with('status', 'Biaya telah dihapus');
    }

    public function select2kontak(Request $request)
    {
        $searchTerm = $request->term;

        $selectKontak = kontak::where('nama_kontak', 'like', '%' . $searchTerm . '%')
            ->select('id_kontak', 'nama_kontak')
            ->get();

        return response()->json([$selectKontak]);
    }

    public function editKosong($id_akb)
    {
        $metodePembayaran = kasbank::all();
        $akunBedit = akunBiaya::find($id_akb);
        return view('admin.user.edit-akunBiaya', compact('akunBedit', 'metodePembayaran'));
    }

    public function updateKosong(Request $request, $id_akb)
    {
        $akunBedit = akunBiaya::find($id_akb);

        $hpsformat = str_replace(['Rp.', ',', '.'], '', $request->jumlah);
        $ubhFormat = (int)$hpsformat;


        $slkasbank = kasbank::where('nama_akun', $request->payment)->first();

        if ($slkasbank && $ubhFormat > $slkasbank->saldo_akhir) {
        return redirect()->route('akun-biaya')->withErrors(['Saldo Tidak Mencukupi!']);
        }

        $akunBedit->id_kategori_biaya = $request->id_kategori;
        $akunBedit->kd_akun_biaya = $request->kd_akun_biaya;
        $akunBedit->nama_akun_biaya = $request->nama_akun_biaya;
        $akunBedit->nama_penerima = $request->nama_penerima;
        $akunBedit->metode_pembayaran = $request->payment;
        $akunBedit->kategori_akun_biaya = $request->nama_kategori;
        $akunBedit->jumlah = $ubhFormat;
        $akunBedit->save();

        $slkasbank = kasbank::where('nama_akun', $request->payment)->first();
        $saldo_akhir = $slkasbank->saldo_akhir;
        $saldo_akhir = max(0, $saldo_akhir - $ubhFormat);
        $slkasbank->saldo_akhir = $saldo_akhir;
        $slkasbank->save();

        $note = 'Memperbarui Biaya ID: ' . $akunBedit->kd_akun_biaya;
        log::create([
            'id_user' => auth()->user()->id,
            'notes' => $note,
        ]);

        return redirect()->route('akun-biaya')->with('status', 'biaya berhasil diedit!');
    }

    public function select2kategori(Request $request)
    {
        $searchTerm = $request->term;

        $selectKategori = kategoribiaya::where('nama_kategori', 'like', '%' . $searchTerm . '%')
            ->where('status', '=', 'aktif')
            ->select('id_kategori_biaya', 'nama_kategori')
            ->get();

        return response()->json([$selectKategori]);
    }
}
