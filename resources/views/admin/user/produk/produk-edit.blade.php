@extends('layouts.app', [
    'title' => 'Edit Produk',
    'breadcrumbs' => [
        'Produk',
        'Edit Produk',
    ],
])

@section('content')

<div class="card">
    <div class="card-header">
        <h4 class="card-title">Edit Produk</h4>
    </div>
    <div class="card-content">
        <div class="card-body">
            <form action="/update/produk/{{ $produk->id }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_produk">Nama Produk<span style="color:red"> *</span></label>
                            <input type="text" class="form-control" id="nama_produk" name="nama_produk" value="{{ $produk->nama_produk }}" required>
                        </div>
                        <div class="form-group">
                            <label for="unit">Unit<span style="color:red"> *</span></label>
                            <select class="form-control" id="unit" name="unit">
                                <option value="buah">buah</option>
                                <option value="pcs">pcs</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="harga_jual">Harga Jual</label>
                            <input type="text" class="form-control" id="nilai_produk" name="harga_jual" value="{{ $produk->harga_jual}}">
                        </div>
                        <div class="form-group">
                            <label for="harga_beli">Harga Beli</label>
                            <input type="text" class="form-control" id="nilai_produk" name="harga_beli" value="{{ $produk->harga_beli }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="kode_produk">Kode Produk</label>
                            <input type="text" class="form-control" id="kode_produk" name="kode_produk" value="{{ $produk->kode_produk }}">
                        </div>
                        <div class="form-group">
                            <label>Kategori Produk</label>
                            <select class="form-control katprod" name="kategori_produk">
                                @if($produk->kategori)
                                <option value="{{ $produk->kategori->id }}">{{ $produk->kategori->nama_produk_kategori }}</option>
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="total_stok">Total Stok</label>
                            <input type="number" class="form-control" id="total_stok" name="total_stok" value="{{ $produk->total_stok }}">
                        </div>
                        <div class="form-group">
                            <label for="minimun_stok">Minimun Stok</label>
                            <input type="number" class="form-control" id="minimun_stok" name="minimun_stok" value="{{ $produk->minimun_stok }}">
                        </div>
                    </div>
                </div>
                <a href="/produk" class="btn btn-outline-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary">Edit</button>
            </form>
        </div>
    </div>
</div>

@endsection
