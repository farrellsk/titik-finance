@extends('layouts.app', [
    'title' => 'Edit Kategori Produk',
    'breadcrumbs' => [
        'Produk',
        'Edit Kategori Produk',
    ],
])

@section('content')

<div class="card">
    <div class="card-header">
        <h4 class="card-title">Edit Kategori Produk</h4>
    </div>
    <div class="card-content">
        <div class="card-body">
            <form action="/kategori/produk/update/{{ $prokat->id }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="nama_produk_kategori">Nama Kategori Produk</label>
                    <input type="text" class="form-control" id="nama_produk_kategori" name="nama_produk_kategori" value="{{$prokat->nama_produk_kategori}}" required>
                </div>
                <a href="/kategori-produk" class="btn btn-outline-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary">Edit</button>
            </form>
        </div>
    </div>
</div>

@endsection
