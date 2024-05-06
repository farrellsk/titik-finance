<?php

namespace App\Http\Controllers;

use App\Models\akunBiaya;
use App\Models\User;
use App\Models\kasbank;
use App\Models\log;
use App\Models\transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $total_saldo = DB::table('akun_kas')->sum('saldo_akhir');

        $total_biaya = akunBiaya::sum('jumlah');
        $totalPengeluaran = transaksi::where('tipe', 'pengeluaran')
            ->where('status', 'Success')
            ->withSum('hasManyTransaksi as total_pengeluaran', 'total')
            ->get();
        $pengeluaran = $totalPengeluaran->sum('total_pengeluaran');

        $totalPemasukan = transaksi::where('tipe', 'pemasukan')
            ->where('status', 'Success')
            ->withSum('hasManyTransaksi as total_pemasukan', 'total')
            ->get();
        $pemasukan = $totalPemasukan->sum('total_pemasukan');

        //grafik kolom semua data
        $barBiaya = akunBiaya::selectRaw('SUM(jumlah) as total')
            ->selectRaw("DATE_FORMAT(created_at, '%b %Y') as bulan")
            ->whereYear('created_at', '2024')
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%b %Y')"))
            ->orderBy('created_at', 'ASC')
            ->get();

            // dd($barBiaya);


        $barPengeluaran = transaksi::selectRaw("DATE_FORMAT(tanggal, '%b %Y') as bulan")
            ->selectSub(function ($query) {
                $query->selectRaw('SUM(total)')->from('transaction_item')->whereColumn('transaction_item.id_transaksi', 'transaction.id');
            }, 'total_pengeluaran')
            ->where('tipe', 'pengeluaran')
            ->where('status', 'Success')
            ->whereYear('tanggal', '2024')
            ->groupBy(DB::raw("DATE_FORMAT(tanggal, '%b %Y')"))
            ->get();
        // dd($barPengeluaran);

        $barPemasukan = transaksi::selectRaw("DATE_FORMAT(tanggal, '%b %Y') as bulan")
            ->selectSub(function ($query) {
                $query->selectRaw('SUM(total)')->from('transaction_item')->whereColumn('transaction_item.id_transaksi', 'transaction.id');
            }, 'total_pemasukan')
            ->where('tipe', 'pemasukan')
            ->where('status', 'Success')
            ->whereYear('tanggal', '2024')
            ->groupBy(DB::raw("DATE_FORMAT(tanggal, '%b %Y')"))
            ->get();

        $bulanT = transaksi::select(DB::raw("DATE_FORMAT(tanggal, '%b %Y') as bulan"))
            ->whereYear('tanggal', '2024')
            ->GroupBy('bulan')
            ->orderBy('tanggal', 'ASC')
            ->pluck('bulan');


        $mergedData = [];

        foreach ($bulanT as $bulan) {
            $data = [
                'bulan' => $bulan,
                'pemasukan' => $barPemasukan->firstWhere('bulan', $bulan)['total_pemasukan'] ?? 0,
                'pengeluaran' => $barPengeluaran->firstWhere('bulan', $bulan)['total_pengeluaran'] ?? 0,
                'biaya' => $barBiaya->firstWhere('bulan', $bulan)['total'] ?? 0,
            ];


            $mergedData[] = $data;
        }

        return view(
            'home',
            [
                'total_saldo' => $total_saldo, 'pengeluaran' => $pengeluaran, 'pengeluaran' => $pengeluaran,
                'total_biaya' => $total_biaya, 'pemasukan' => $pemasukan, 'barBiaya' => $barBiaya,
                'barPengeluaran' => $barPengeluaran, 'barPemasukan' => $barPemasukan,
                'mergedData' => $mergedData
            ]
        );
    }

    public function logIndex()
    {
        $t_log = log::all();

        return view('admin.user.logging', compact('t_log'));
    }
}
