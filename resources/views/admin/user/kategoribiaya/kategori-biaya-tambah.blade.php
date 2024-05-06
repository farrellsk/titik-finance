@extends('layouts.app', [
    'title' => 'Tambah Kategori',
    'breadcrumbs' => [
        'Kategori Biaya',
        'Tambah Kategori',
    ],
])

@section('content')

<div class="card">
    <div class="card-header">
        <h4 class="card-title">Tambah Kategori</h4>
    </div>
    <div class="card-content">
        <div class="card-body">
            <form action="{{route ('tambah-kategori')}}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="nama_kategori_biaya">Nama Kategori</label>
                    <input type="text" class="form-control @error('nama_kategori') is-invalid
                    @enderror" id="nama_kategori_biaya" name="nama_kategori" value="" required>
                    @error('nama_kategori')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="aktif">Aktif</option>
                        <option value="non-aktif">Non-Aktif</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Tambah Data</button>
            </form>
        </div>
    </div>
</div>

@endsection
