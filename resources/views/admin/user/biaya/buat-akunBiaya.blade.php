@extends('layouts.app', [
    'title' => 'Tambah biaya',
    'breadcrumbs' => ['Tambah Biaya']
])

@section('content')
    <div class="card">
        <div class="card-content">
            <div class="card-body">
                <div class="modal-content">
                    <form action="{{ route('insert-akunB') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Nomor</label>
                                <input type="text" class="form-control" name="kd_biaya" value="{{ $nextId }}"
                                    required readonly>
                            </div>
                          
                            <div class="form-group">
                                <label>Nama Penerima</label>
                                <select class="form-control" name="nama_penerima" id="nama_penerima" required>
                                    <!-- Options will be dynamically loaded by Select2 -->
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Kategori</label>
                                <select class="form-control" name="nama_kategori" id="nama_kategoriselect" required>
                                    <!-- Options will be dynamically loaded by Select2 -->
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="payment">Metode Pembayaran</label>
                                <select name="payment" id="payment-method" class="form-control">
                                    @foreach($metodePembayaran as $metode)
                                        <option value="{{ $metode->nama_akun }}" data-saldo_akhir="{{ $metode->saldo_akhir }}">
                                            {{ $metode->payment }} - {{ $metode->nama_akun }}
                                        </option>
                                    @endforeach
                                </select>
                                <span id="saldo-text"></span>
                            </div>


                            <div class="form-group">
                                <label>Jumlah</label>
                                <input type="text" name="jumlah" id="harga" class="form-control"
                                    placeholder="Masukkan Jumlah" />
                                    <span id="saldo-error" style="color: red;"></span>
                            </div>

                            <div class="form-group">
                                <label for="alamat">Keterangan</label>
                                <textarea class="form-control" id="keterangan" name="keterangan"></textarea>
                            </div>

                            <div class="modal-footer">
                                <a href="/akun-biaya" class="btn btn-outline-secondary">Kembali</a>
                                <button type="submit" class="btn btn-primary">Tambah</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection
