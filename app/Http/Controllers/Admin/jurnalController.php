<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\akunb;
use App\Models\kasbank;
use App\Models\kontak;
use App\Models\mutasi;
use App\Models\transaksi;
use App\Models\triggerSaldo;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class jurnalController extends Controller
{

    public function index()
    {

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
            ->groupBy('t.id', 'item.id', 'pd.nama_produk', 'k.nama_kontak')
            ->orderBy('saldo.id')
            ->orderBy('t.payment_via')
            ->get();


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
            ->groupBy('t.id', 'item.id', 'pd.nama_produk', 'k.nama_kontak')
            ->orderBy('saldo.id')
            ->orderBy('t.payment_via')
            ->get();

        $biaya = DB::table('akun_biaya')
            ->join('view_update_biaya_history as biaya', 'biaya.id_tbiaya', '=', 'akun_biaya.kd_akun_biaya')
            ->select('akun_biaya.*', 'biaya.saldo_setelahnya')
            ->get();

        $doo = kasbank::first() ? kasbank::first()->saldo_akhir : 0;

        $sal = $doo;

        $juntrak = [];

        foreach ($pemasukan as $pem) {

            $pay = kasbank::where('id_kas', $pem->payment_via)->select('nama_akun', 'payment')->first();

            $juntrak[] = [
                'tanggal' => $pem->tanggal,
                'kontak' => $pay->payment . '-' . $pay->nama_akun,
                'produk' => $pem->nama_produk,
                'deskripsi' => 'Pemasukan',
                'kirim' => null,
                'terima' => 'Rp. ' . number_format($pem->total, 0, ',', '.'),
                'saldo' => 'Rp. ' . number_format($pem->saldo_setelahnya, 0, ',', '.'),
                'status' => $pem->status
            ];
        }

        foreach ($pengeluaran as $peng) {

            $payluar = kasbank::where('id_kas', $peng->payment_via)->select('nama_akun', 'payment')->first();

            $juntrak[] = [
                'tanggal' => $peng->tanggal,
                'kontak' => $payluar->payment . '-' . $payluar->nama_akun,
                'produk' => $peng->nama_produk,
                'deskripsi' => 'Pengeluaran',
                'kirim' => 'Rp. ' . number_format($peng->total, 0, ',', '.'),
                'terima' => null,
                'saldo' => 'Rp. ' . number_format($peng->saldo_setelahnya, 0, ',', '.'),
                'status' => $peng->status
            ];
        }


        foreach ($biaya as $b) {

            $payb = kasbank::where('nama_akun', $b->metode_pembayaran)->select('nama_akun', 'payment')->first();
            $aya = $b->jumlah;
            $doo -= $aya;
            $juntrak[] = [
                'tanggal' => $b->created_at,
                'kontak' => $payb ? $payb->payment . '-' . $payb->nama_akun : "Akun telah dihapus",
                'produk' => null,
                'deskripsi' => 'Biaya',
                'kirim' => 'Rp. ' . number_format($aya, 0, ',', '.'),
                'terima' => null,
                'saldo' => 'Rp. ' . number_format($b->saldo_setelahnya, 0, ',', '.'),
                'status' => 'Success'
            ];
        }

        $mutasis = mutasi::all();
        foreach ($mutasis as $mutasi) {
            $namaAkunAsal = kasbank::where('id_kas', $mutasi->akun_asal)->value('nama_akun');
            $namaAkunTujuan = kasbank::where('id_kas', $mutasi->akun_tujuan)->value('nama_akun');
            $noRekAsal = kasbank::where('id_kas', $mutasi->akun_asal)->value('no_rekening');
            $noRekTujuan = kasbank::where('id_kas', $mutasi->akun_tujuan)->value('no_rekening');

            $asal = '<strong>' . $namaAkunAsal . '</strong>' . ' - ' . $noRekAsal;
            $tujuan = '<strong>' . $namaAkunTujuan . '</strong>' . ' - ' . $noRekTujuan;

            $deskripsi = 'Mutasi <br><span style="font-size: 10px;">Transfer dari Bank ' . $namaAkunAsal .
                            '<br>'. ' ke Bank ' . $namaAkunTujuan . '</span>';

            $juntrak[] = [
                'tanggal' => $mutasi->waktu,
                'kontak' => $asal  . '<br>' . 'Ke ' . $tujuan,
                'produk' => null,
                'deskripsi' => $deskripsi,
                'kirim' => 'Rp. ' . number_format($mutasi->jumlah_mutasi, 0, ',', '.'),
                'terima' => 'Rp. ' . number_format($mutasi->jumlah_mutasi, 0, ',', '.'),
                'saldo' => null,
                'status' => 'Success'
            ];
        }

        usort($juntrak, function ($a, $b) {
            $timeA = date('H:i:s', strtotime($a['tanggal']));
            $timeB = date('H:i:s', strtotime($b['tanggal']));
            return strcmp($timeA, $timeB);
        });

        return view('admin.user.jurnal.jurnal', compact('juntrak'));
    }
}
