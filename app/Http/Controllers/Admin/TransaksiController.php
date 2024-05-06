<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\detailkasbank;
use Illuminate\Http\Request;
use App\Models\kasbank;
use App\Models\kontak;
use App\Models\log;
use App\Models\setting;
use App\Models\produk;
use App\Models\transaksi;
use App\Models\transAtachment;
use App\Models\transItem;
use App\Models\triggerSaldo;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use NumberToWords\NumberToWords;

class TransaksiController extends Controller
{
    public function view()
    {

        $transaksi = transaksi::where('tipe', 'pengeluaran')->with('user', 'kasbank')->latest()->get();

        $transItem = transaksi::withSum('hasManyTransaksi as total_pengeluaran', 'total')->get();

        $setting = setting::all()->first();


        return view('admin.user.transaksi.pengeluaran.index', [
            'transaksi' => $transaksi,
            'transItem' => $transItem,
            'setting' => $setting
        ]);
    }

    public function create()
    {
        $tanggal = Carbon::now();

        $ubhformat = $tanggal->format('Y-m-d');
        $kasbank = kasbank::all();

        return view('admin.user.transaksi.pengeluaran.add', [
            'ubhformat' => $ubhformat,
            'kasbank' => $kasbank,
        ]);
    }

    public function insert(Request $request)
    {

        $request->validate([
            'kontak' => 'required',
            'payment' => 'required',
            'status' => 'required',
            'dokument.*' => 'nullable',
        ]);

        // Insert Transaksi
        $user = Auth::id();
        $transaksi = transaksi::create([
            'tipe' => 'pengeluaran',
            'created_by' => $user,
            'contact_id' => $request->kontak,
            'payment_via' => $request->payment,
            'status' => $request->status,
            'notes' => $request->note,
            'tanggal' => $request->tanggal,
            'do_date' => $request->tanggal_jatuh_tempo
        ]);

        if ($request->hasFile('dokument')) {
            $file = $request->dokument;
            $multifile = $file->getClientOriginalName();
            $file->move(public_path('uploads'), $multifile);
            transAtachment::create([
                'id_transaksi' => $transaksi->id,
                'url' => $multifile,
            ]);
        }

        // Insert Detail Transaksi
        $produk = $request->input('produk');
        $jumlah = $request->input('jumlah');
        $total = $request->input('total');
        $nominal = $request->input('nominal');

        foreach ($produk as $index => $item) {

            $hps = str_replace(['Rp.', '.', ','], '', $nominal[$index]);
            $nom = (int)$hps;

            $uang = str_replace(['Rp.', '.', ','], '', $total[$index]);
            $value = (int)$uang;

            $item = transItem::create([
                'id_transaksi' => $transaksi->id,
                'id_produk' => $item,
                'qty' => $jumlah[$index],
                'amount' => $nom,
                'total' => $value,
            ]);
        }

        // Kasbank
        $slkasbank = kasbank::where('id_kas', $request->payment)->first();

        $totalArray = [];

        if ($request->status == 'Success') {
            session(['saldo' => $slkasbank->saldo_akhir]);
            $saldo_sebelumnya = $slkasbank->saldo_akhir;

            $idItems = transItem::where('id_transaksi', $transaksi->id)->pluck('id')->toArray();

            foreach ($total as $index => $data) {
                $cleanString = str_replace(["Rp. ", "."], "", $data);
                $convertedValue = intval($cleanString);
                $totalArray[] = $convertedValue;
            }

            foreach ($totalArray as $index => $dt) {
                if (isset($idItems[$index])) {
                    $saldo_setelahnya = max(0, $saldo_sebelumnya - $dt);

                    triggerSaldo::create([
                        'id_item' => $idItems[$index],
                        'id_transaksi' => $transaksi->id,
                        'saldo_sebelumnya' => $saldo_sebelumnya,
                        'saldo_setelahnya' => $saldo_setelahnya,
                        'jenis_transaksi' => 'pengeluaran',
                        'id_kasbank' => $transaksi->payment_via,
                    ]);

                    $saldo_sebelumnya = $saldo_setelahnya;
                }
            }

            $slkasbank->saldo_akhir = $saldo_sebelumnya;
            $slkasbank->save();
        }

        $note = 'Menambah Transaksi (Pembelian) Baru, ID: ' . $transaksi->id . ' - Status: ' . $request->status;
        log::create([
            'id_user' => auth()->user()->id,
            'notes' => $note,
        ]);

        return redirect()->route('transaksi.index')->with('status', 'data berhasil ditambahkan');
    }

    public function edit($id)
    {
        $transaksi = transaksi::with('hasManyTransaksi', 'hasManyTrans_Attachment')->find($id);
        $transaksi->tanggal = Carbon::parse($transaksi->tanggal)->format('Y-m-d');

        $kasbank = kasbank::all();
        return view('admin.user.transaksi.pengeluaran.edit', compact('transaksi', 'kasbank'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::id();

        $request->validate([
            'kontak' => 'required',
            'payment' => 'required',
            'status' => 'required',
            'dokument.*' => 'nullable|mimes:jpeg,png,pdf,docx',
        ]);

        $tanggal = \Carbon\Carbon::parse($request->tanggal)->format('Y-m-d') . ' ' . \Carbon\Carbon::now()->format('H:i:s');

        $transaksi = Transaksi::find($id);
        $transaksi->tipe = 'pengeluaran';
        $transaksi->created_by = $user;
        $transaksi->payment_via = $request->payment;
        $transaksi->contact_id = $request->kontak;
        $transaksi->status = $request->status;
        $transaksi->tanggal = $tanggal;
        $transaksi->do_date = $request->tanggal_jatuh_tempo;
        $transaksi->notes = $request->note;
        $transaksi->save();

        $note = 'Memperbarui Transaksi (Pembelian) ID: ' . $transaksi->id . ' - Status: ' . $request->status;
        log::create([
            'id_user' => auth()->user()->id,
            'notes' => $note,
        ]);

        if ($request->hasFile('dokument')) {
            $files = $request->dokument;
            foreach ($files as $file) {
                $filename = $file->getClientOriginalName();
                $file->move(public_path('uploads'), $filename);

                $attachment = new transAtachment();
                $attachment->id_transaksi = $transaksi->id;
                $attachment->url = $filename;
                $attachment->save();
            }
        }

        $existingIds = $transaksi->hasManyTransaksi->pluck('id')->toArray();
        $slkasbank = kasbank::where('id_kas', $request->payment)->first();
        $saldo_sebelumnya = $slkasbank->saldo_akhir;

        foreach ($request->input('produk', []) as $index => $produk) {
            $itemId = $request->item_id[$index] ?? null;

            if ($itemId && in_array($itemId, $existingIds)) {
                $item = transItem::find($itemId);
            } else {
                $item = new transItem();
                $item->id_transaksi = $transaksi->id;
            }

            $item->id_produk = $produk;
            $item->qty = $request->jumlah[$index] ?? null;
            $item->amount = intval(str_replace(['Rp.', '.', ','], '', $request->nominal[$index] ?? '0'));
            $item->total = intval(str_replace(['Rp.', '.', ','], '', $request->total[$index] ?? '0'));
            $item->save();

            if ($transaksi->status == 'Success') {
                $saldo_setelahnya = max(0, $saldo_sebelumnya - $item->total);

                triggerSaldo::updateOrCreate(
                    ['id_item' => $item->id],
                    [
                        'id_transaksi' => $transaksi->id,
                        'saldo_sebelumnya' => $saldo_sebelumnya,
                        'saldo_setelahnya' => $saldo_setelahnya,
                        'jenis_transaksi' => 'pengeluaran',
                        'id_kasbank' => $transaksi->payment_via
                    ]
                );

                $saldo_sebelumnya = $saldo_setelahnya;
            }

            if (($key = array_search($itemId, $existingIds)) !== false) {
                unset($existingIds[$key]);
            }
        }

        if ($transaksi->status == 'Success') {
            $slkasbank->saldo_akhir = $saldo_sebelumnya;
            $slkasbank->save();
        }

        transItem::whereIn('id', $existingIds)->delete();

        return redirect()->route('transaksi.index')->with('status', 'Data berhasil diperbarui');
    }
    
    public function lampiran($id)
    {
        $kasbank = Kasbank::findOrFail($id);
        return view('kasbank.show', compact('kasbank'));
    }


    public function deleteDoc($id)
    {
        $doc = transAtachment::findOrFail($id);
        $doc->delete();

        return response()->json(['message' => 'Dokumen berhasil dihapus']);
    }

    public function hapustransaksi($id)
    {
        $pengeluaran = transaksi::findOrFail($id);

        $jumlahB =  transItem::where('id_transaksi', $id)->sum('total');

        $sltransaksi = kasbank::where('id_kas', $pengeluaran->payment_via)->first();
        if ($sltransaksi) {
            $sltransaksi->saldo_akhir += $jumlahB;
            $sltransaksi->save();
        }
        $pengeluaran->delete();

        $note = 'Menghapus Transaksi (Pembelian) ID: ' . $id . ' - Status: ' . $pengeluaran->status;
        log::create([
            'id_user' => auth()->user()->id,
            'notes' => $note,
        ]);

        return redirect()->route('transaksi.index')->with('status', 'transaksi telah dihapus');
    }

    public function unduhPengeluaran($id_trans)
    {

        $setting = setting::all()->first();
        $transaksi = transaksi::with('user', 'kasbank')->where('id', $id_trans)->first();
        $transItem = transItem::where('id_transaksi', $id_trans)->get();
        $total = $transItem->sum('total');
        $transAttch = transAtachment::all()->where('id_transaksi', $id_trans)->first();

        if ($transaksi) {
            $carbonWaktu = Carbon::parse($transaksi->tanggal);

            $carbonWaktu->setTimezone('Asia/Jakarta');

            $transaksi->tanggal = $carbonWaktu->format('F d, Y');

            $tgl = $transaksi->do_date = $carbonWaktu->format('F d, Y');

            $tempo = $transaksi->do_date = $carbonWaktu->format('d/m/y');
        }

        $pengeluaran = array(
            $transaksi->id,
            $transaksi->user->name,
            $transaksi->kasbank->payment,
            $transaksi->notes,
            $transaksi->status,
            $transaksi->tanggal,
            $transaksi->contact_id,
            $transaksi->kasbank->nama_akun,
            $tgl,
            $tempo,
            $transaksi->kasbank->no_rekening,
            $transaksi->contact->nama_kontak,
            $transAttch
        );

        $note = 'Melihat Faktur Transaksi (Pembelian) dengan ID: ' . $id_trans;
        log::create([
            'id_user' => auth()->user()->id,
            'notes' => $note,
        ]);

        $imagePath = $setting ? public_path('images/setting/logo/' . $setting->logo) : "";
        $imageData = $imagePath ? base64_encode(file_get_contents($imagePath)) : "";

        $pdf = PDF::loadView('admin.user.transaksi.pengeluaran.faktur', [
            'pengeluaran' => $pengeluaran,
            'transItem' => $transItem,
            'total' => $total,
            'imageData' => $imageData,
            'setting' => $setting
        ])->setOptions(['defaultFont' => 'sans-serif'])
            ->setPaper('A4', 'portrait');

        return $pdf->stream('faktur-pembelian.pdf');
    }

    public function unduhInvoice($id)
    {

        $setting = setting::all()->first();
        $transaksi = transaksi::with('user', 'kasbank')->where('id', $id)->first();
        $transItem = transItem::where('id_transaksi', $id)->get();
        $total = $transItem->sum('total');

        $numberToWords = new NumberToWords();
        $numberTransformer = $numberToWords->getNumberTransformer('id');

        $uang = $numberTransformer->toWords($total);


        if ($transaksi) {
            $carbonWaktu = Carbon::parse($transaksi->tanggal);

            $carbonWaktu->setTimezone('Asia/Jakarta');

            $transaksi->tanggal = $carbonWaktu->format('d F Y');
        }

        $pengeluaran = array(
            $transaksi->id,
            $transaksi->user->name,
            $transaksi->kasbank->payment,
            $transaksi->notes,
            $transaksi->status,
            $transaksi->tanggal,
            $transaksi->contact_id,
            $transaksi->kasbank->nama_akun,
            $transaksi->kasbank->no_rekening,
            $transaksi->contact->nama_kontak,
        );

        $note = 'Melihat Invoice Transaksi (Pembelian) dengan ID: ' . $id;
        log::create([
            'id_user' => auth()->user()->id,
            'notes' => $note,
        ]);

        $imagePath = $setting ? public_path('images/setting/logo/' . $setting->logo) : "";
        $imageData = $imagePath ? base64_encode(file_get_contents($imagePath)) : "";

        $image = $setting ? public_path('images/setting/image_ttd/' . $setting->image_ttd) : "";
        $ttd = $image ? base64_encode(file_get_contents($image)) : "";

        $pdf = PDF::loadView('admin.user.transaksi.pengeluaran.invoice', [
            'pengeluaran' => $pengeluaran,
            'transItem' => $transItem,
            'total' => $total,
            'setting' => $setting,
            'imageData' => $imageData,
            'uang' => $uang,
            'ttd' => $ttd
        ])->setOptions(['defaultFont' => 'sans-serif'])
            ->setPaper('A4', 'portrait');

        return $pdf->stream('invoice-pembelian.pdf');
    }

    public function select2pengproduk(Request $request)
    {
        $searchTerm = $request->term;

        $selectProduk1 = produk::where('nama_produk', 'like', '%' . $searchTerm . '%')
            ->select('id', 'nama_produk')
            ->get();

        return response()->json([$selectProduk1]);
    }


    public function select2pengkontak(Request $request)
    {
        $selectKontak1 = kontak::where('tipe_kontak', $request->tipe)
            ->select('id_kontak', 'nama_kontak')
            ->get();

        return response()->json([$selectKontak1]);
    }

    public function hargaJProduk($id)
    {
        $produk = produk::find($id);

        if (!$produk) {
            return response()->json(['error' => 'Produk not found'], 404);
        }

        return response()->json(['harga_jual' => $produk->harga_jual]);
    }
}
