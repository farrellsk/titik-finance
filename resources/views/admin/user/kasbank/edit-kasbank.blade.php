@extends('layouts.app', [
    'title' => 'Edit Kas & Bank',
    'breadcrumbs' => ['Edit'],
])

@section('content')
    <div class="card">
        <div class="card-content">
            <div class="card-body">
                <div class="modal-content">
                    <form action="/update/kasbank/{{ $akun_kas->id_kas }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="payment">Untuk Payment Method</label>
                                <select name="payment" id="payment-method" class="form-control">
                                    @foreach ($paymentMethods as $method)
                                        <option value="{{ $method }}"
                                            @if ($akun_kas->payment == $method) selected @endif>{{ $method }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <input type="hidden" class="form-control" name="kode_kas" value="{{ $akun_kas->kd_kas }}">
                            <div class="form-group">
                                <label>Nama Akun</label>
                                <input type="text" class="form-control" name="nama_akun"
                                    value="{{ $akun_kas->nama_akun }}">
                            </div>
                            <div class="form-group">
                                <label>Saldo Awal</label>
                                <input type="text" name="saldo_awal" id="harga" class="form-control"
                                    placeholder="Masukkan Saldo" value="@currency($akun_kas->saldo_awal)">
                            </div>
                            <div class="form-group bank-fields">
                                <label>Nama Pemilik Rekening</label>
                                <input type="text" class="form-control" name="nama_pemilik"
                                    value="{{ $akun_kas->nama_rekening }}">
                            </div>
                            <div class="form-group bank-fields">
                                <label>Nomor Rekening</label>
                                <input type="number" class="form-control @error('no_rekening') is-invalid @enderror"
                                    name="no_rekening" value="{{ $akun_kas->no_rekening }}">
                                @error('no_rekening')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="modal-footer">
                                <a href="/home" class="btn btn-outline-secondary">Batal</a>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    </div>
@endsection
