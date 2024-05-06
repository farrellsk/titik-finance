<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\log;
use App\Models\setting;
use Illuminate\Http\Request;

class settingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $t_setting = setting::all()->first();
        return view('admin.user.setting.setting-index', compact('t_setting'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // return view('admin.user.setting.setting-tambah');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $id = $request->input('id');

        $rules = [
            'nama_perusahaan' => 'required',
            'alamat' => 'required',
            'email' => 'required',
            'no_hp' => 'required',
            'nama_ttd' => 'required',
            'kecamatan' => 'required',
            'kota' => 'required',
        ];

        // Tambahkan validasi file hanya jika sedang membuat entri baru
        if (empty($id)) {
            $rules['logo'] = 'required|file|image|max:2048';
            $rules['image_ttd'] = 'required|file|image|max:2048';
        }

        $request->validate($rules);

        $setting = $id ? Setting::findOrFail($id) : new Setting;

        $data = [
            'nama_perusahaan' => $request->nama_perusahaan,
            'alamat' => $request->alamat,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'nama_ttd' => $request->nama_ttd,
            'kecamatan' => $request->kecamatan,
            'kota' => $request->kota
        ];

        if ($request->hasFile('logo')) {
            $logoNya = $request->file('logo')->getClientOriginalName();
            $request->file('logo')->move(public_path('images/setting/logo'), $logoNya);
            $data['logo'] = $logoNya;
        } elseif ($id) {
            $data['logo'] = $setting->logo;
        }

        if ($request->hasFile('image_ttd')) {
            $imageTtdNya = $request->file('image_ttd')->getClientOriginalName();
            $request->file('image_ttd')->move(public_path('images/setting/image_ttd'), $imageTtdNya);
            $data['image_ttd'] = $imageTtdNya;
        } elseif ($id) {
            $data['image_ttd'] = $setting->image_ttd;
        }

        if ($id) {
            $setting->update($data);
        } else {
            $setting = Setting::create($data);
        }

        $note = 'Mengubah Setting';
        log::create([
            'id_user' => auth()->user()->id,
            'notes' => $note,
        ]);

        return redirect()->route('setting-index')->with('status', 'Setting berhasil disimpan.');
    }
}
