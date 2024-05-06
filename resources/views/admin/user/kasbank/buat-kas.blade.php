@extends('layouts.app', [
    'title' => 'Tambah Kas & Bank',
    'breadcrumbs' => ['Tambah'],
])

@section('content')
    <div class="card">
        <div class="card-content">
            <div class="card-body">
                <div class="modal-content">
                    <form action="{{ route('add-kasbank') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="payment">Untuk Payment Method</label>
                                <select name="payment" id="payment-method" class="form-control">
                                    <option value="Kas" {{   session('selected_payment_method') == 'Kas' ? 'selected' : '' }}>Kas</option>
                                    <option value="Bank" {{ session('selected_payment_method') == 'Bank' ? 'selected' : '' }}>Bank</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Nama Akun</label>
                                <input type="text" class="form-control" name="nama_akun" value="{{ old('nama_akun') }}"
                                    required>
                                @error('nama_akun')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Saldo Awal</label>
                                <input type="text" name="saldo_awal" id="harga" class="form-control"
                                    placeholder="Masukkan Saldo" required />
                                <!-- Tambahkan validasi jika diperlukan -->
                            </div>
                            <div class="form-group bank-fields">
                                <label>Nama Pemilik Rekening</label>
                                <input type="text" class="form-control" name="nama_pemilik"
                                    value="{{ old('nama_pemilik') }}" >
                                @error('nama_pemilik')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group bank-fields">
                                <label>Nomor Rekening</label>
                                <input type="number" class="form-control @error('no_rekening') is-invalid @enderror"
                                    name="no_rekening" value="{{ old('no_rekening') }}" >
                                @error('no_rekening')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="modal-footer">
                                <a href="/kasbank" class="btn btn-outline-secondary">Kembali</a>
                                <button type="submit" class="btn btn-primary">Tambah</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>

@endsection
