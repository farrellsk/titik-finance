<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\akunBiaya;
use App\Models\kasbank;
use App\Models\LaporanPemasukan;
use App\Models\LaporanPengeluaran;
use App\Models\log;
use App\Models\setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index()
    {
        return view('admin.user.laporan');
    }

    public function laporanPeng(Request $request)
    {
        $dari = $request->query('dari');
        $sampai = $request->query('sampai');

        if ($dari && $sampai) {
            $darii = date_create($dari);
            $sampaii = date_create($sampai);
            $dari = date_format($darii, 'd-m-Y');
            $sampai = date_format($sampaii, 'd-m-Y');
        }

        $notes = $request->query('notes_ts');
        $status = $request->query('status_ts');
        $total_min = $request->query('total_min');
        $total_max = $request->query('total_max');

        $hps_min = str_replace(['Rp.', '.', ','], '', $total_min);
        $nom_min = (int)$hps_min;

        $hps_max = str_replace(['Rp.', '.', ','], '', $total_max);
        $nom_max = (int)$hps_max;


        $dt_peng = LaporanPengeluaran::query();

        if ($dari && $sampai) {
            $dt_peng->whereBetween('tanggal', [$dari, $sampai]);
        }

        if ($notes) {
            $dt_peng->where('notes', $notes);
        }

        if ($status) {
            $dt_peng->where('status', $status);
        }

        if ($total_min && $total_max) {
            $dt_peng->whereBetween('total', [$nom_min, $nom_max]);
        }
        $harga = $dt_peng->sum('total');

        $pengeluaran = $dt_peng->get();
        $setting = setting::all()->first();

        $imagePath = $setting ? public_path('images/setting/logo/' . $setting->logo) : "";
        $imageData = base64_encode(file_get_contents($imagePath));


        $pdf = PDF::loadView('admin.user.unduh.laporan-pengeluaran', [
            'imageData' => $imageData,
            'pengeluaran' => $pengeluaran,
            'harga' => $harga,
            'setting' => $setting,
            'imageData' => $imageData,
        ])->setOptions(['defaultFont' => 'sans-serif'])
            ->setPaper('A4', 'portrait');

        $note = 'Melihat/Mengunduh Laporan Pembelian';
        log::create([
            'id_user' => auth()->user()->id,
            'notes' => $note,
        ]);

        return $pdf->stream('unduh-pengeluaran.pdf');
    }

    public function laporanPem(Request $request)
    {
        $dari = $request->query('dari');
        $sampai = $request->query('sampai');

        if ($dari && $sampai) {
            $darii = date_create($dari);
            $sampaii = date_create($sampai);
            $dari = date_format($darii, 'd-m-Y');
            $sampai = date_format($sampaii, 'd-m-Y');
        }

        $notes = $request->query('notes_ts');
        $status = $request->query('status_ts');
        $total_min = $request->query('total_min');
        $total_max = $request->query('total_max');

        $dt_peng = LaporanPemasukan::query();

        if ($dari && $sampai) {
            $dt_peng->whereBetween('tanggal', [$dari, $sampai]);
        }

        if ($notes) {
            $dt_peng->where('notes', $notes);
        }

        if ($status) {
            $dt_peng->where('status', $status);
        }

        if ($total_min && $total_max) {
            $dt_peng->whereBetween('total', [$total_min, $total_max]);
        }
        $harga = $dt_peng->sum('total');

        $pemasukan = $dt_peng->get();

        $setting = setting::all()->first();


        $imagePath = $setting ? public_path('images/setting/logo/' . $setting->logo) : "";
        $imageData = $imagePath ? base64_encode(file_get_contents($imagePath)) : "";

        $pdf = PDF::loadView('admin.user.unduh.laporan-pemasukan', [
            'imageData' => $imageData,
            'pemasukan' => $pemasukan,
            'harga' => $harga,
            'setting' => $setting
        ])->setOptions(['defaultFont' => 'sans-serif'])
            ->setPaper('A4', 'portrait');

        $note = 'Melihat/Mengunduh Laporan Penjualan';
        log::create([
            'id_user' => auth()->user()->id,
            'notes' => $note,
        ]);

        return $pdf->stream('unduh-pemasukan.pdf');
    }

    public function laporanBiaya(Request $request)
    {

        $nbiaya = $request->query('n_biaya');
        $npenerima = $request->query('n_penerima');
        $payment = $request->query('payment');
        $kategori = $request->query('kategori');
        $total_min = $request->query('total_min');
        $total_max = $request->query('total_max');

        $biaya = akunBiaya::query();

        if ($nbiaya) {
            $biaya->where('nama_akun_biaya', $nbiaya);
        }
        if ($npenerima) {
            $biaya->where('nama_penerima', $npenerima);
        }
        if ($payment) {
            $biaya->where('metode_pembayaran', $payment);
        }
        if ($kategori) {
            $biaya->where('kategori_akun_biaya', $kategori);
        }
        if ($total_min && $total_max) {
            $biaya->whereBetween('jumlah', [$total_min, $total_max]);
        }

        $biayas = $biaya->get();

        $setting = setting::all()->first();

        $jumlah = $biayas->sum('jumlah');
        $imagePath = $setting ? public_path('images/setting/logo/' . $setting->logo) : "";
        $imageData = $imagePath ? base64_encode(file_get_contents($imagePath)) : "";

        $pdf = PDF::loadView('admin.user.unduh.laporan-biaya', [
            'imageData' => $imageData,
            'biayas' => $biayas,
            'jumlah' => $jumlah,
            'setting' => $setting
        ])->setOptions(['defaultFont' => 'sans-serif'])
            ->setPaper('A4', 'portrait');

        $note = 'Melihat/Mengunduh Laporan Biaya';
        log::create([
            'id_user' => auth()->user()->id,
            'notes' => $note,
        ]);

        return $pdf->stream('unduh-biaya.pdf');
    }

    public function laporanJun(Request $request)
    {
        $transactions = collect();
        $dari = request('dari');
        $sampai = request('sampai');
        $jenis = request('jenis');

        $juntrak = [];


        $setting = setting::all()->first();

        //biaya
        if ($jenis == 'biaya') {
            $biaya = DB::table('akun_biaya')
            ->join('view_update_biaya_history as biaya', 'biaya.id_tbiaya', '=', 'akun_biaya.kd_akun_biaya')
            ->select('akun_biaya.*', 'biaya.saldo_setelahnya', 'akun_biaya.nama_penerima', 'akun_biaya.nama_akun_biaya as ket', 'akun_biaya.jumlah as total')
            ->when($dari, function ($query, $dari) {
                return $query->where('akun_biaya.created_at', '>=', $dari);
            });

            $biayas = $biaya->get();

        foreach ($biayas as $b) {

            $payb = kasbank::where('nama_akun', $b->metode_pembayaran)->select('nama_akun', 'payment')->first();
            $aya = $b->jumlah;
            $tgl = $b->created_at;
            $tgal = date_create($tgl);
            $tgaal = date_format($tgal, 'd-m-Y');
            $juntrak[] = [
                'tanggal' => $tgaal,
                'kontak' => $payb->payment . '-' . $payb->nama_akun,
                'deskripsi' => $b->nama_akun_biaya,
                'terima' => null,
                'kirim' => 'Rp. ' . number_format($aya, 0, ',', '.'),
                'saldo' => 'Rp. ' . number_format($b->saldo_setelahnya, 0, ',', '.'),
                'status' => 'Success'
            ];
        }

            if ($juntrak == false) {
                return redirect()->back()->withErrors('Data tidak ada!');
            }
        }

        //transaksi
        if ($jenis == 'pengeluaran' || $jenis == 'pemasukan') {
            $transactionsQuery = DB::table('transaction')
            ->join('transaction_item as item', 'item.id_transaksi', '=', 'transaction.id')
            ->join('produk as pd', 'pd.id', '=', 'item.id_produk')
            ->join('update_saldo_kasbank_histories as saldo', 'saldo.id_item', '=', 'item.id')
            ->select('transaction.*', 'saldo.saldo_setelahnya','item.total', 'pd.nama_produk')
            ->where('transaction.status', 'Success')
            ->when($dari, function ($query, $dari) {
                return $query->where('transaction.tanggal', '>=', $dari);
            })
            ->when($sampai, function ($query, $sampai) {
                return $query->where('transaction.tanggal', '<=', $sampai);
            })
            ->when($jenis, function ($query, $jenis) {
                if (in_array($jenis, ['pemasukan', 'pengeluaran'])) {
                    return $query->where('transaction.tipe', $jenis);
                }
            })
            ->orderBy('item.id')
            ->orderBy('transaction.payment_via');

        $transactions = $transactionsQuery->get();

        $total = 0;

        foreach ($transactions as $trans) {

            $pay = kasbank::where('id_kas', $trans->payment_via)->select('nama_akun', 'payment')->first();

            $tgl = $trans->tanggal;
            $tgal = date_create($tgl);
            $tgaal = date_format($tgal, 'd-m-Y');

            if ($trans->tipe === 'pemasukan') {
                $kirim = 'Rp. ' . number_format($trans->total, 0, ',', '.');
                $terima = null;
                $total += $trans->total;
            } else {
                $kirim = null;
                $terima = 'Rp. ' . number_format($trans->total, 0, ',', '.');
                $total += $trans->total;
             }

            $juntrak[] = [
                'tanggal' => $tgaal,
                'kontak' => $pay->payment . '-' . $pay->nama_akun,
                'deskripsi' => $trans->nama_produk,
                'terima' => $terima,
                'kirim' => $kirim,
                'saldo' => 'Rp. ' . number_format($trans->saldo_setelahnya, 0, ',', '.'),
                'status' => $trans->status,
                'total' => 'Rp. ' . number_format($total, 0, ',', '.'),
            ];

        }

        if($juntrak == false){
            return redirect()->back()->withErrors('Data tidak ada!');
        }
    }
    //mutasi

        if($jenis == 'mutasi'){
            $mutasi = DB::table('mutasi')
            ->join('update_saldo_kasbank_histories as saldo', 'saldo.id_mutasi', '=', 'mutasi.id')
            ->select('mutasi.*', 'saldo.saldo_setelahnya')
            ->when($dari, function ($query, $dari) {
                return $query->where('mutasi.waktu', '>=', $dari);
            })
            ->when($sampai, function ($query, $sampai) {
                return $query->where('mutasi.waktu', '<=', $sampai);
            });

            $mutasis = $mutasi->get();

        foreach ($mutasis as $m) {
            $namaAkunAsal = kasbank::where('id_kas', $m->akun_asal)->value('nama_akun');
            $namaAkunTujuan = kasbank::where('id_kas', $m->akun_tujuan)->value('nama_akun');
            $noRekAsal = kasbank::where('id_kas', $m->akun_asal)->value('no_rekening');
            $noRekTujuan = kasbank::where('id_kas', $m->akun_tujuan)->value('no_rekening');

            $asal = '<strong>' . $namaAkunAsal . '</strong>' . ' - ' . $noRekAsal;
            $tujuan = '<strong>' . $namaAkunTujuan . '</strong>' . ' - ' . $noRekTujuan;

            $deskripsi = 'Mutasi <br><span style="font-size: 10px;">Transfer dari Bank ' . $namaAkunAsal .
                            '<br>'. ' ke Bank ' . $namaAkunTujuan . '</span>';

            $tgl = $m->waktu;
            $tgal = date_create($tgl);
            $tgaal = date_format($tgal, 'd-m-Y');
            $juntrak[] = [
                'tanggal' => $tgaal,
                'kontak' =>  $asal  . '<br>' . 'Ke ' . $tujuan,
                'deskripsi' => $deskripsi,
                'terima' => 'Rp. ' . number_format($m->jumlah_mutasi, 0, ',', '.'),
                'kirim' => 'Rp. ' . number_format($m->jumlah_mutasi, 0, ',', '.'),
                'saldo' => 'Rp. ' . number_format($m->saldo_setelahnya, 0, ',', '.'),
                'status' => 'Success'
            ];
        }

        if($juntrak == false){
            return redirect()->back()->withErrors('Data tidak ada!');
        }

        }

        $imagePath = $setting ? public_path('images/setting/logo/' . $setting->logo) : "";
        $imageData = $imagePath ? base64_encode(file_get_contents($imagePath)) : "";

        $pdf = PDF::loadView('admin.user.jurnal.pdf-jurnal', ['juntrak' => $juntrak, 'jenis' => $jenis, 'transactions' => $transactions, 'imageData' => $imageData,  'setting' => $setting])
            ->setOptions(['defaultFont' => 'sans-serif'])
            ->setPaper('A4', 'portrait');

        $note = 'Melihat/Mengunduh Laporan Jurnal';
        log::create([
            'id_user' => auth()->user()->id,
            'notes' => $note,
        ]);

        return $pdf->stream('pdf.jurnal');
    }


    public function backupDBS()
    {
        $mysqlHostName = env('DB_HOST');
        $mysqlUserName = env('DB_USERNAME');
        $mysqlPassword = env('DB_PASSWORD');
        $DbName = env('DB_DATABASE');
        $backup_name = "mybackup.sql";

        $tables = array("users", "akun_biaya", "akun_kas", "detail_kasbank", "detail_kasbank","kategori_biaya", "kontak", "migrations", "model_has_permissions", "model_has_roles", "mutasi" , "password_resets", "permissions", "personal_access_tokens","produk","produk_kategori", "roles", "role_has_permissions", "setting" ,"transaction", "transaction_attachment", "transaction_item", "update_saldo_kasbank_histories", "view_update_biaya_history", "log");

        try {
            $connect = new \PDO("mysql:host=$mysqlHostName;dbname=$DbName;charset=utf8", $mysqlUserName, $mysqlPassword, array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));

            $output = "SET FOREIGN_KEY_CHECKS = 0;\n\n";

            foreach ($tables as $table) {
                $output .= "DROP TABLE IF EXISTS `$table`;\n";
                $show_table_query = "SHOW CREATE TABLE `$table`";
                $statement = $connect->prepare($show_table_query);
                $statement->execute();
                $show_table_result = $statement->fetchAll();

                foreach ($show_table_result as $show_table_row) {
                    $output .= "\n\n" . $show_table_row["Create Table"] . ";\n\n";
                }

                $select_query = "SELECT * FROM `$table`";
                $statement = $connect->prepare($select_query);
                $statement->execute();
                while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
                    $output .= "INSERT INTO `$table` (`" . implode("`, `", array_keys($row)) . "`) VALUES ";
                    $valuesArray = [];
                    foreach ($row as $key => $value) {
                        $value = $value === NULL ? 'NULL' : $connect->quote($value);
                        $valuesArray[] = $value;
                    }
                    $output .= "(" . implode(", ", $valuesArray) . ");\n";
                }
            }
            $output .= "\nSET FOREIGN_KEY_CHECKS = 1;\n";


            $file_name = 'titik_finance_backup_on_' . date('y-m-d') . '.sql';
            if (!file_put_contents($file_name, $output)) {

                throw new \Exception("Gagal menyimpan file backup.");
            }

            $note = 'Membackup Database';
            log::create([
                'id_user' => auth()->user()->id,
                'notes' => $note,
            ]);

            return response()->download($file_name)->deleteFileAfterSend(true);
        } catch (\PDOException $e) {

            return response()->json(['error' => $e->getMessage()]);
        } catch (\Exception $e) {

            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function restoreDB(Request $request)
    {
        $sqlFile = $request->file('sql_file');
        if ($sqlFile) {
            $content = file_get_contents($sqlFile);
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            DB::unprepared($content);
            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            $note = 'Merestore Database';
            log::create([
                'id_user' => auth()->user()->id,
                'notes' => $note,
            ]);

            return redirect()->route('laporan.index')
                ->with('status', 'Data berhasil dipulihkan dari file SQL.');
        }else{
            return redirect()->route('laporan.index')
            ->with('error', 'Gagal memulihkan data. Pastikan Anda mengunggah file SQL yang benar.');
        }


    }
}
