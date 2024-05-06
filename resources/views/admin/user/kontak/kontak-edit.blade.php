@extends('layouts.app', [
    'title' => 'Edit Kontak',
    'breadcrumbs' => [
        'kontak',
        'Edit Kontak',
    ],
])

@section('content')

<div class="card">
    <div class="card-header">
        <h4 class="card-title">Edit Kontak</h4>
    </div>
    <div class="card-content">
        <div class="card-body">
            <form action="/update/kontak/{{$kontak->id_kontak}}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="nama_kontak">Nama Kontak</label>
                    <input type="text" class="form-control" id="nama_kontak" name="nama_kontak" value="{{ $kontak->nama_kontak }}">
                </div>
                <div class="form-group">
                    <label>Tipe Kontak</label><br>

                    <div class="form-check form-check-inline">
                        <input type="radio" id="pelanggan" name="tipe_kontak" class="form-check-input" value="Pelanggan"
                            {{ $kontak->tipe_kontak == 'Pelanggan' ? 'checked' : '' }}>
                        <label for="Pelanggan" class="form-check-label">Pelanggan</label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input type="radio" id="karyawan" name="tipe_kontak" class="form-check-input" value="Karyawan"
                            {{ $kontak->tipe_kontak == 'Karyawan' ? 'checked' : '' }}>
                        <label for="Karyawan" class="form-check-label">Karyawan</label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input type="radio" id="suplier" name="tipe_kontak" class="form-check-input" value="Suplier"
                            {{ $kontak->tipe_kontak == 'Suplier' ? 'checked' : '' }}>
                        <label for="Suplier" class="form-check-label">Suplier</label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input type="radio" id="lainnya" name="tipe_kontak" class="form-check-input" value="Lainnya"
                            {{ $kontak->tipe_kontak == 'Lainnya' ? 'checked' : '' }}>
                        <label for="Lainnya" class="form-check-label">Lainnya</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="no_telp">Nomor Telepon</label>
                    <input type="text" class="form-control @error('no_telp') is-invalid @enderror" id="no_telp" name="no_telp" value="{{ $kontak->no_telp }}">
                    @error('no_telp')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
                </div>
                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <textarea class="form-control" id="alamat" name="alamat">{{ $kontak->alamat }}</textarea>
                </div>

                <a href="/kontak" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
</div>

@endsection
