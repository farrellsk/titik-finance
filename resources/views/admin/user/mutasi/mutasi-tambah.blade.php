@extends('layouts.app', [
    'title' => 'Mutasi',
    'breadcrumbs' => ['Mutasi', 'Tambah Mutasi'],
])

@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Tambah Mutasi Baru</h4>
        </div>
        <div class="card-content">
            <div class="card-body">
                <form action="{{ route('mutasi-add') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Akun Asal<span style="color:red"> *</span></label>
                                <select class="form-control namaKasbank akun_asal" name="akun_asal">
                                    @foreach ($mutKas as $mt)
                                        {{ $mt->nama_akun }}
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Akun Tujuan<span style="color:red"> *</span></label>
                                <select class="form-control namaKasbank" name="akun_tujuan">
                                    @foreach ($mutKas as $mt)
                                        {{ $mt->nama_akun }}
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="waktu">Tanggal Transaksi</label>
                                <input type="date" class="form-control" id="waktu" name="waktu">
                            </div>
                        </div>
                        <div class="col-md-6"></div>
                        <div class="col-md-12">
                            <div class="alert alert-success text-primary" role="alert">
                                <b style="color: blue">
                                    Saldo Akun/Kas Asal
                                    <br>
                                    <span class="saldo-akhir"></span>
                                </b>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="jumlah_mutasi">Jumlah Mutasi<span style="color:red"> *</span></label>
                                <input type="text" class="form-control" id="mutasi" name="jumlah_mutasi">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="biaya_layanan">Biaya Layanan<span style="color:red"> *</span></label>
                                <input type="text" class="form-control" id="mutasi" name="biaya_layanan" value="0">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="keterangan">Keterangan</label>
                                <textarea class="form-control" id="keterangan" name="keterangan"></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="lampiran">File/Lampiran</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="lampiran" name="lampiran"
                                        accept=".png, .jpg, .jpeg, .doc, .pdf, .docx">
                                    <label class="custom-file-label" for="lampiran">Choose file</label>
                                </div>
                                <small class="form-text" style="font-weight: bold">File yang di izinkan : png, jpg, jpeg,
                                    doc, pdf, docx. Maksimal 2mb</small>
                            </div>
                        </div>
                    </div>
                    <a href="/mutasi" class="btn btn-outline-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </form>
            </div>
        </div>
    </div>
@endsection
