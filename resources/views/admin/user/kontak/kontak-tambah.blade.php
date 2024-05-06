@extends('layouts.app', [
    'title' => 'Tambah Kontak',
    'breadcrumbs' => [
        'kontak',
        'Tambah Kontak',
    ],
])

@section('content')

<div class="card">
    <div class="card-header">
        <h4 class="card-title">Tambah Kontak Baru</h4>
    </div>
    <div class="card-content">
        <div class="card-body">
            <form action="{{ route('kontak.store')}}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="nama_kontak">Nama Kontak</label>
                    <input type="text" class="form-control" id="nama_kontak" name="nama_kontak" required>
                </div>
                <div class="form-group">
                    <label>Tipe Kontak</label><br>
                    <div class="form-check form-check-inline">
                        <input type="radio" id="pelanggan" name="tipe_kontak" class="form-check-input" value="Pelanggan">
                        <label for="Pelanggan" class="form-check-label">Pelanggan</label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input type="radio" id="karyawan" name="tipe_kontak" class="form-check-input" value="Karyawan">
                        <label for="Karyawan" class="form-check-label">Karyawan</label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input type="radio" id="suplier" name="tipe_kontak" class="form-check-input" value="Suplier">
                        <label for="Suplier" class="form-check-label">Suplier</label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input type="radio" id="lainnya" name="tipe_kontak" class="form-check-input" value="Lainnya">
                        <label for="Lainnya" class="form-check-label">Lainnya</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="no_telp">Nomor Telepon</label>
                    <input type="text" class="form-control " id="no_telp" name="no_telp" >
                </div>
                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <textarea class="form-control" id="alamat" name="alamat"></textarea>
                </div>
                <a href="/kontak" class="btn btn-outline-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary">Tambah</button>
            </form>
        </div>
    </div>
</div>

@endsection
