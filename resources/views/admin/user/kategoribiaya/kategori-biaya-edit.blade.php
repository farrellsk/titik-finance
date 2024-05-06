@extends('layouts.app', [
    'title' => 'Edit Kategori',
    'breadcrumbs' => [
        'KategoriBiaya',
        'Edit Kategori',
    ],
])

@section('content')

<div class="card">
    <div class="card-header">
        <h4 class="card-title">Edit Kategori</h4>
    </div>
    <div class="card-content">
        <div class="card-body">
            <form action="/update/kategori/{{$t_kategoribiaya->id_kategori_biaya}}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="nama_kontak">Id</label>
                    <input type="text" class="form-control" id="id_kategori_biaya" name="id_kategori_biaya" value="{{ $t_kategoribiaya->id_kategori_biaya }}" required readonly>
                </div>

                <div class="form-group">
                    <label for="nama_kategori">Nama Kategori</label>
                    <input type="text" class="form-control @error('nama_kategori') is-invalid
                    @enderror" id="nama_kategori" name="nama_kategori" value="{{ $t_kategoribiaya->nama_kategori }}" required>
                    @error('nama_kategori')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="aktif" {{ $t_kategoribiaya->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="non-aktif" {{ $t_kategoribiaya->status == 'non-aktif' ? 'selected' : '' }}>Non-Aktif</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Update Kategori</button>
            </form>
        </div>
    </div>
</div>

@endsection
