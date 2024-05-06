<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\akunBiaya;
use App\Models\detailkasbank;
use App\Models\kasbank;
use App\Models\log;
use App\Models\mutasi;
use App\Models\transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class kasBankController extends Controller
{

    public function index()
    {
        $dt_kas = kasbank::all();

        return view('admin.user.kasbank.kasbank', compact('dt_kas'));
    }

    public function detkasbank($id)
    {

        $juntrak = [];
        // Data Pemasukan
        $pemasukan = DB::table('transaction as t')
            ->Join('transaction_item as item', 'item.id_transaksi', '=', 't.id')
            ->Join('update_saldo_kasbank_histories as saldo', 'saldo.id_item', '=', 'item.id')
            ->Join('produk as pd', 'pd.id', '=', 'item.id_produk')
            ->Join('kontak as k', 'k.id_kontak', '=', 't.contact_id')
            ->select(
                't.*',
                DB::raw('MAX(saldo.saldo_setelahnya) as saldo_setelahnya'),
                'item.id',
                'item.total',
                'pd.nama_produk',
                'k.nama_kontak',
                'saldo.id_item'
            )
            ->where('t.tipe', 'pemasukan')
            ->where('t.status', 'Success')
            ->where('t.payment_via', $id)
            ->groupBy('t.id', 'item.id', 'pd.nama_produk', 'k.nama_kontak')
            ->orderBy('saldo.id')
            ->orderBy('t.payment_via')
            ->get();


        foreach ($pemasukan as $pem) {

            $pay = kasbank::where('id_kas', $id)->select('nama_akun', 'payment')->first();

            $juntrak[] = [
                'tanggal' => $pem->tanggal,
                'tipe' => 'Pejualan',
                'kontak' => $pem->nama_kontak,
                'kas_bank' => $pay->nama_akun,
                'terima' => 'Rp. ' . number_format($pem->total, 0, ',', '.'),
                'kirim' => null,
                'saldo' => 'Rp. ' . number_format($pem->saldo_setelahnya, 0, ',', '.'),
                'status' => $pem->status
            ];
        }

        // Data Pengeluaran
        $pengeluaran = DB::table('transaction as t')
            ->Join('transaction_item as item', 'item.id_transaksi', '=', 't.id')
            ->Join('update_saldo_kasbank_histories as saldo', 'saldo.id_item', '=', 'item.id')
            ->Join('produk as pd', 'pd.id', '=', 'item.id_produk')
            ->Join('kontak as k', 'k.id_kontak', '=', 't.contact_id')
            ->select(
                't.*',
                DB::raw('MAX(saldo.saldo_setelahnya) as saldo_setelahnya'),
                'item.id',
                'item.total',
                'pd.nama_produk',
                'k.nama_kontak',
                'saldo.id_item'
            )
            ->where('t.tipe', 'pengeluaran')
            ->where('t.status', 'Success')
            ->where('t.payment_via', $id)
            ->groupBy('t.id', 'item.id', 'pd.nama_produk', 'k.nama_kontak')
            ->orderBy('saldo.id')
            ->orderBy('t.payment_via')
            ->get();

        // dd($pengeluaran);
        foreach ($pengeluaran as $peng) {

            $payluar = kasbank::where('id_kas', $id)->select('nama_akun', 'payment')->first();

            $juntrak[] = [
                'tanggal' => $peng->tanggal,
                'tipe' => 'Pembelian',
                'kontak' => $peng->nama_kontak,
                'kas_bank' => $payluar->nama_akun,
                'terima' => null,
                'kirim' => 'Rp. ' . number_format($peng->total, 0, ',', '.'),
                'saldo' => 'Rp. ' . number_format($peng->saldo_setelahnya, 0, ',', '.'),
                'status' => $peng->status
            ];
        }

        // Data Biaya
        $kasbank = kasbank::where('id_kas', $id)->select('nama_akun', 'payment')->first();
        $biaya = DB::table('akun_biaya')
            ->join('view_update_biaya_history as biaya', 'biaya.id_tbiaya', '=', 'akun_biaya.kd_akun_biaya')
            ->select('akun_biaya.*', 'biaya.saldo_setelahnya')
            ->where('metode_pembayaran', $kasbank->nama_akun)
            ->get();

        foreach ($biaya as $b) {

            $payb = kasbank::where('id_kas', $id)->select('nama_akun', 'payment')->first();
            $juntrak[] = [
                'tanggal' => $b->created_at,
                'tipe' => 'Biaya',
                'kontak' => $b->nama_penerima,
                'kas_bank' => $payb->nama_akun,
                'terima' => null,
                'kirim' => 'Rp ' . number_format($b->jumlah, 0, ',', '.'),
                'saldo' => 'Rp. ' . number_format($b->saldo_setelahnya, 0, ',', '.'),
                'status' => 'Success'
            ];
        }

        // Data Mutasi
        // Asal
        $mutasiAkunAsal = DB::table('mutasi')
            ->join('update_saldo_kasbank_histories as saldo', 'mutasi.id', '=', 'saldo.id_mutasi')
            ->join('akun_kas as kas', 'mutasi.akun_asal', '=', 'kas.id_kas')
            ->select('mutasi.*', 'saldo.saldo_sebelumnya', 'kas.nama_akun')
            ->where('akun_asal', $id)
            ->get();

        // Tujuan
        $mutasiAkunTujuan = DB::table('mutasi')
            ->join('update_saldo_kasbank_histories as saldo', 'mutasi.id', '=', 'saldo.id_mutasi')
            ->join('akun_kas as kas', 'mutasi.akun_tujuan', '=', 'kas.id_kas')
            ->select('mutasi.*', 'saldo.saldo_setelahnya', 'kas.nama_akun')
            ->where('akun_tujuan', $id)
            ->get();

        foreach ($mutasiAkunAsal as $mts) {
            $total = $mts->jumlah_mutasi + $mts->biaya_layanan;
            $juntrak[] = [
                'tanggal' => $mts->waktu,
                'tipe' => 'Mutasi',
                'kontak' => null,
                'kas_bank' => $mts->nama_akun,
                'terima' => null,
                'kirim' => 'Rp. ' . number_format($total, 0, ',', '.'),
                'saldo' => 'Rp. ' . number_format($mts->saldo_sebelumnya, 0, ',', '.'),
                'status' => 'Success'
            ];
        }


        foreach ($mutasiAkunTujuan as $mts) {
            $juntrak[] = [
                'tanggal' => $mts->waktu,
                'tipe' => 'Mutasi',
                'kontak' => null,
                'kas_bank' => $mts->nama_akun,
                'terima' => 'Rp. ' . number_format($mts->jumlah_mutasi, 0, ',', '.'),
                'kirim' => null,
                'saldo' => 'Rp. ' . number_format($mts->saldo_setelahnya, 0, ',', '.'),
                'status' => 'Success'
            ];
        }

        usort($juntrak, function ($a, $b) {
            $timeA = date('H:i:s', strtotime($a['tanggal']));
            $timeB = date('H:i:s', strtotime($b['tanggal']));
            return strcmp($timeA, $timeB);
        });

        return view('admin.user.kasbank.detail-kasbank', compact('juntrak'));
    }


    public function create()
    {
        return view('admin.user.kasbank.buat-kas');
    }


    public function store(Request $request)
    {
        $kdKB = kasbank::latest('id_kas')->first();

        if ($kdKB == null) {
            $hapusformat = str_replace(['Rp.', '.', ','], '', $request->saldo_awal);
            $ubhformat = (int)$hapusformat;

            session(['selected_payment_method' => request('payment')]);

            if ($request->no_rekening) {
                $request->validate([
                    'no_rekening' => 'required|unique:akun_kas'
                ]);
            }
            $kdkas = '#0001';

            kasbank::create([
                'kd_kas' => $kdkas,
                'payment' => $request->payment,
                'nama_akun' => $request->nama_akun,
                'saldo_awal' => $ubhformat,
                'saldo_akhir' => $ubhformat,
                'nama_rekening' => $request->nama_pemilik,
                'no_rekening' => $request->no_rekening
            ]);
        } else {
            $lastNumber = (int)substr($kdKB->kd_kas, 1);
            $nextNumber = $lastNumber + 1;
            $nextId = '#' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

            $hapusformat = str_replace(['Rp.', '.', ','], '', $request->saldo_awal);
            $ubhformat = (int)$hapusformat;

            session(['selected_payment_method' => request('payment')]);

            if ($request->no_rekening) {
                $request->validate([
                    'no_rekening' => 'required|unique:akun_kas'
                ]);
            }

            kasbank::create([
                'kd_kas' => $nextId,
                'payment' => $request->payment,
                'nama_akun' => $request->nama_akun,
                'saldo_awal' => $ubhformat,
                'saldo_akhir' => $ubhformat,
                'nama_rekening' => $request->nama_pemilik,
                'no_rekening' => $request->no_rekening
            ]);
        }

        $note = 'Menambah Kas/Bank - ' . $request->nama_akun;
        log::create([
            'id_user' => auth()->user()->id,
            'notes' => $note,
        ]);

        return redirect()->route('kasbank')->with('status', 'Kas Bank telah ditambahkan');
    }


    public function show(string $id)
    {
    }


    public function edit(string $id_kas)
    {
        $akun_kas = kasbank::find($id_kas);

        $paymentMethods = ['Kas', 'Bank'];
        return view('admin.user.edit-kasbank', compact('akun_kas', 'paymentMethods'));
    }


    public function update(Request $request, string $id_kas)
    {
        $id_kasbank = kasbank::find($id_kas);

        $hapusformat = str_replace(['Rp.', '.', ','], '', $request->saldo_awal);

        $ubhformat = (int)$hapusformat;

        session(['selected_payment_method' => request('payment')]);

        if ($request->no_rekening == $id_kasbank->no_rekening) {
            $id_kasbank->kd_kas = $request->kode_kas;
            $id_kasbank->payment = $request->payment;
            $id_kasbank->nama_akun = $request->nama_akun;
            $id_kasbank->saldo_awal = $ubhformat;
            $id_kasbank->nama_rekening = $request->nama_pemilik;
            $id_kasbank->no_rekening = $request->no_rekening;
            $id_kasbank->save();
        } else {
            if ($request->no_rekening) {
                $request->validate([
                    'no_rekening' => 'required|unique:akun_kas'
                ]);
            }

            $id_kasbank->kd_kas = $request->kode_kas;
            $id_kasbank->payment = $request->payment;
            $id_kasbank->nama_akun = $request->nama_akun;
            $id_kasbank->saldo_awal = $ubhformat;
            $id_kasbank->nama_rekening = $request->nama_pemilik;
            $id_kasbank->no_rekening = $request->no_rekening;
            $id_kasbank->save();
        }

        $note = 'Mengedit Kas/Bank - ' . $request->nama_akun;
        log::create([
            'id_user' => auth()->user()->id,
            'notes' => $note,
        ]);

        return redirect()->route('kasbank')->with('status', 'Kas Bank berhasil diedit!');
    }


    public function destroy(string $id_kas)
    {
        $kb = kasbank::findOrFail($id_kas);
        $kb->delete();

        $note = 'Menghapus Kas/Bank - ' . $kb->nama_akun;
        log::create([
            'id_user' => auth()->user()->id,
            'notes' => $note,
        ]);

        return redirect()->back()->with('status', 'Kas Bank berhasil dihapus!');
    }
}
