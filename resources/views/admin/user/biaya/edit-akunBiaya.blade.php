@extends('layouts.app', [
    'title' => 'Edit biaya',
    'breadcrumbs' => ['Edit']
])

@section('content')
    <div class="card">
        <div class="card-content">
            <div class="card-body">
                <form method="POST" action="{{ route('update-biaya', ['id_akb' => $akunBedit->id_akun_biaya]) }}">
                    @csrf

                    <input type="hidden" name="id_kategori" value="{{ $akunBedit->id_kategori_biaya }}">

                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nomor</label>
                            <input type="text" class="form-control" name="kd_akun_biaya" value="{{ $akunBedit->kd_akun_biaya }}"
                                required readonly>
                        </div>
                     
                        <div class="form-group">
                            <label>Nama Penerima</label>
                            <select class="form-control" name="nama_penerima" id="nama_penerima" required>
                                <!-- Options will be dynamically loaded by Select2 -->
                                <option value="{{ $akunBedit->nama_penerima }}" selected>{{ $akunBedit->nama_penerima }}</option>
                            </select>
                        </div>


                        <div class="form-group">
                            <label for="payment">Metode Pembayaran</label>
                            <select name="payment" id="payment-method" class="form-control" value="{{ $akunBedit->metode_pembayaran }}">
                                @foreach($metodePembayaran as $metode)
                                    <option value="{{ $metode->nama_akun }}" data-saldo_akhir="{{ $metode->saldo_akhir }}">
                                        {{ $metode->payment }} - {{ $metode->nama_akun }}</option>
                                @endforeach
                            </select>
                            <span id="saldo-text"></span>
                        </div>


                        <div class="form-group">
                            <label>Jumlah</label>
                            <input type="text" name="jumlah" id="harga" class="form-control"
                                placeholder="Masukkan Jumlah" value="@currency($akunBedit->jumlah)"/>
                                <span id="saldo-error" style="color: red;"></span>
                        </div>

                        <div class="form-group">
                            <label>Kategori</label>
                            <select class="form-control" name="nama_kategori" id="nama_kategoriselect" required>
                                <!-- Options will be dynamically loaded by Select2 -->
                                <option value="{{ $akunBedit->kategori_akun_biaya }}" selected>{{ $akunBedit->kategori_akun_biaya }}</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="alamat">Keterangan</label>
                            <textarea class="form-control" id="nama_akun_biaya" name="nama_akun_biaya">{{ $akunBedit->nama_akun_biaya }}</textarea>
                        </div>

                        <div class="modal-footer">
                            <a href="/akun-biaya" class="btn btn-outline-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                </form>
            </div>
        </div>
    </div>
@endsection
