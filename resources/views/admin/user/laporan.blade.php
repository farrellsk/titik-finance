@extends('layouts.app', [
    'title' => 'Unduh Data',
    'breadcrumbs' => ['list data'],
])

@section('content')
        <div class="card">
            <div class="card-content">
                <div class="card-body">

            <!-- Filter Penjual -->
            <div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="filterModalLabel">Filter Data Laporan Pengeluaran</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                    <div class="modal-body">
                        <div class="info-biaya" style="width: 100%; height: 30px; background-color: rgb(213, 230, 254); text-align: center; padding-top: 2px; padding-bottom: 2px; margin-bottom: 10px; color: blue; border:1px solid blue">
                            Inputan Dapat diisi atau tidak sama sekali
                          </div>
                        <form method="get" action="{{ route('unduh.pengeluaran') }}" target="_blank">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="dari">Dari:</label>
                                    <input type="date" name="dari" class="form-control" value="{{ request('dari') }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="sampai">Sampai:</label>
                                    <input type="date" name="sampai" class="form-control" value="{{ request('sampai') }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="notes_ts">Catatan:</label>
                                <input type="text" name="notes_ts" class="form-control" value="{{ request('notes_ts') }}">
                            </div>
                            <div class="form-group">
                                <label for="status_ts">Status:</label>
                                <input type="text" name="status_ts" class="form-control" value="{{ request('status_ts') }}">
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="total_min">Total Min:</label>
                                    <input type="text" name="total_min" id="nilai_produk" class="form-control" value="{{ request('total_min') }}" placeholder="Rp.">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="jumlah_max_biaya">Total Max:</label>
                                    <input type="text" name="total_max" id="nilai_produk" class="form-control" value="{{ request('total_max') }}" placeholder="Rp.">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-info">Unduh</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!------------------->


         <!-- Filter Pemasukan -->
            <div class="modal fade" id="filterPemasukan" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="filterModalLabel">Filter Data Laporan Pemasukan</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                    <div class="modal-body">
                        <div class="info-biaya" style="width: 100%; height: 30px; background-color: rgb(213, 230, 254); text-align: center; padding-top: 2px; padding-bottom: 2px; margin-bottom: 10px; color: blue; border:1px solid blue">
                            Inputan Dapat diisi atau tidak sama sekali
                          </div>
                        <form method="get" action="{{ route('unduh.pemasukan') }}" target="_blank">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="dari">Dari:</label>
                                    <input type="date" name="dari" class="form-control" value="{{ request('dari') }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="sampai">Sampai:</label>
                                    <input type="date" name="sampai" class="form-control" value="{{ request('sampai') }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="notes_ts">Catatan:</label>
                                <input type="text" name="notes_ts" class="form-control" value="{{ request('notes_ts') }}">
                            </div>
                            <div class="form-group">
                                <label for="status_ts">Status:</label>
                                <input type="text" name="status_ts" class="form-control" value="{{ request('status_ts') }}">
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="total_min">Total Min:</label>
                                    <input type="text" name="total_min" id="nilai_produk" class="form-control" value="{{ request('total_min') }}"placeholder="Rp.">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="jumlah_max_biaya">Total Max:</label>
                                    <input type="text" name="total_max" id="nilai_produk" class="form-control" value="{{ request('total_max') }}" placeholder="Rp.">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-info">Unduh</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!------------------->

        <!-- Filter Biaya -->
        <div class="modal fade" id="filterBiaya" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="filterModalLabel">Filter Data Laporan Biaya</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                <div class="modal-body">
                    <div class="info-biaya" style="width: 100%; height: 30px; background-color: rgb(213, 230, 254); text-align: center; padding-top: 2px; padding-bottom: 2px; margin-bottom: 10px; color: blue; border:1px solid blue">
                        Inputan Dapat diisi atau tidak sama sekali
                      </div>
                    <form method="get" action="{{ route('unduh.biaya') }}" target="_blank">
                        <div class="form-group">
                            <label for="n_biaya">Nama Biaya:</label>
                            <input type="text" name="n_biaya" class="form-control" value="{{ request('n_biaya') }}">
                        </div>
                        <div class="form-group">
                            <label for="n_penerima">Nama Penerima:</label>
                            <input type="text" name="n_penerima" class="form-control" value="{{ request('n_penerima') }}">
                        </div>
                        <div class="form-group">
                            <label for="payment">Payment:</label>
                            <input type="text" name="payment" class="form-control" value="{{ request('payment') }}">
                        </div>
                        <div class="form-group">
                            <label for="kategori">Kategori:</label>
                            <input type="text" name="kategori" class="form-control" value="{{ request('kategori') }}">
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="total_min">Total Min:</label>
                                <input type="text" name="total_min" id="nilai_produk" class="form-control" value="{{ request('total_min') }}"placeholder="Rp.">                            </div>
                            <div class="form-group col-md-6">
                                <label for="jumlah_max_biaya">Total Max:</label>
                                <input type="text" name="total_max" id="nilai_produk" class="form-control" value="{{ request('total_max') }}"placeholder="Rp.">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-info">Unduh</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!------------------->

        <!-- Filter Jurnal -->
        <div class="modal fade" id="filterJurnal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="filterModalLabel">Filter Data Laporan Jurnal</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                <div class="modal-body">
                    <form method="get" action="{{ route('pdf.jurnal') }}" target="_blank">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="dari">Dari:</label>
                                <input type="date" name="dari" class="form-control" value="{{ request('dari') }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="sampai">Sampai:</label>
                                <input type="date" name="sampai" class="form-control" value="{{ request('sampai') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="jenis">Jenis Payment<span style="color:red"> *</span></label>
                            <input type="text" name="jenis" class="form-control" value="{{ request('jenis') }}" required>
                            <small class="form-text" style="font-weight: bold">isi seperti : pengeluaran, pemasukan, mutasi</small>
                        </div>
                        <button type="submit" class="btn btn-info">Unduh</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!------------------->

        <div class="table-responsive">
            <table class="table table-hover-animation nowrap scroll-horizontal-vertical" id="crudTable">
                <thead>
                    <tr>
                        <th>Laporan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Laporan Pengeluaran</td>
                        <td><a href="#RekapPendapatan" class="btn btn-success" data-toggle="modal" data-target="#filterModal">
                            Unduh PDF</a></td>
                    </tr>
                    <tr>
                        <td>Laporan Pemasukan</td>
                        <td><a href="#RekapTransaksi" class="btn btn-success" data-toggle="modal" data-target="#filterPemasukan">
                            Unduh PDF</a></td>
                    </tr>
                    <tr>
                        <td>Laporan Biaya</td>
                        <td><a href="#RekapBiaya" class="btn btn-success" data-toggle="modal" data-target="#filterBiaya">
                            Unduh PDF</a></td>
                    </tr>
                    <tr>
                        <td>Laporan Jurnal</td>
                        <td><a href="#RekapJurnal" class="btn btn-success" data-toggle="modal" data-target="#filterJurnal">
                            Unduh PDF</a></td>
                    </tr>
                    <tr>
                        <td>Backup Database</td>
                        <td><a href="/backupDB" class="btn btn-warning"><i class="fa fa-folder" style="margin-right: 5px"></i>Backup</a></td>
                    </tr>
                    <tr>
                        <td>Restore</td>
                        <td>
                            @if (session('status'))
                                <div class="alert alert-success">
                                    {{ session('status') }}
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <form method="post" action="/restoreDatabase" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <input type="file" name="sql_file" id="sql_file" class="form-control">
                                </div>
                                    <button id="save" type="submit" class="btn btn-primary"><i class="fa fa-trash-restore-alt"></i>   Restore Database</button>
                            </form>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
            </div>
        </div>
    </div>
    @endsection

    @push('after_styles')
        <link rel="stylesheet" href="{{ asset('app-assets/vendors/css/tables/datatable/datatables.min.css') }}">
    @endpush

    @push('after_scripts')
        <script src="{{ asset('app-assets/vendors/js/tables/datatable/datatables.min.js') }}"></script>
        <script src="{{ asset('app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js') }}"></script>
    @endpush
