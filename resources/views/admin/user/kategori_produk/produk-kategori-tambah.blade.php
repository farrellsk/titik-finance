@extends('layouts.app', [
    'title' => 'Tambah Kategori Produk',
    'breadcrumbs' => [
        'Produk',
        'Tambah Kategori Produk',
    ],
])

@section('content')

<div class="card">
    <div class="card-header">
        <h4 class="card-title">Tambah Kategori Produk</h4>
    </div>
    <div class="card-content">
        <div class="card-body">
            <form action="{{ route('produk-kategori-store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="nama_produk_kategori">Nama Kategori Produk</label>
                    <input type="text" class="form-control" id="nama_produk_kategori" name="nama_produk_kategori" required>
                </div>
                <a href="/kategori-produk" class="btn btn-outline-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary">Tambah</button>
            </form>
        </div>
    </div>
</div>

@endsection
