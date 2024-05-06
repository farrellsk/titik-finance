@extends('layouts.app', [
    'title' => 'Biaya',
    'breadcrumbs' => ['Biaya', 'List'],
])

@section('content')
    <div class="card">
        <div class="card-content">
            <div class="card-body">
                <div class="d-flex juftify-content-between">
                    <a href="{{ route ('tambah-akunB') }}" class="btn btn-primary">
                        <i class="fa fa-plus"></i> Biaya
                    </a>

                    <button type="button" class="btn btn-info ml-auto" data-toggle="modal" data-target="#filterModal">
                        <i class="fa fa-filter"></i> Filter
                    </button>
                </div>
                <!-- Filter -->
        <div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="filterModalLabel">Filter Biaya</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <form method="get" action="{{ route('akun-biaya') }}">
                            <div class="form-row"> <!---buat memperpendek-->
                                <div class="form-group col-md-6">
                                    <label for="jumlah_min_biaya">Saldo Min:</label>
                                    <input type="number" name="jumlah_min_biaya" class="form-control" value="{{ request('jumlah_min_biaya') }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="jumlah_max_biaya">Saldo Max:</label>
                                    <input type="number" name="jumlah_max_biaya" class="form-control" value="{{ request('jumlah_max_biaya') }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="kategori_akun_biaya">Kategori:</label>
                                <div class="form-group">
                                    <select class="form-control" name="kategori_akun_biaya" id="nama_kategoriselect">
                                        <!-- Options will be dynamically loaded by Select2 -->
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-info">Filter</button>
                            <a href="{{ route('akun-biaya') }}" class="btn btn-danger">Reset</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
                <div class="table-responsive">
                    <table class="table table-hover-animation nowrap scroll-horizontal-vertical" id="crudTable">
                        <thead>
                            <tr>
                                <th>Kode Akun</th>
                                <th>Nama Penerima</th>
                                <th>Metode Pembayaran</th>
                                <th>Kategori</th>
                                <th>Saldo</th>
                                <th>Keterangan</th>
                                    <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($akunB as $b)
                                <tr>
                                    <td>{{ $b->kd_akun_biaya }}</td>
                                    <td>{{ $b->nama_penerima }}</td>
                                    <td>{{ $b->metode_pembayaran }}</td>
                                    <td>{{ $b->kategori_akun_biaya }}</td>
                                    <td>Rp. {{ number_format($b->jumlah) }}</td>
                                    <td>{{ $b->nama_akun_biaya }}</td>
                                    <td>
                                        @if ($b->jumlah == 0)
                                            <a href="{{ route('edit-biaya', ['id_akb' => $b->id_akun_biaya]) }}"
                                                class="btn btn-icon btn-sm btn-warning waves-effect waves-light">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        @endif

                                        <form method="POST"
                                            action="{{ route('hapus-akunb', ['id_akb' => $b->id_akun_biaya]) }}"
                                            id="deleteForm{{ $b->id_akun_biaya }}" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <button title="Delete"
                                            class="btn btn-icon btn-sm btn-danger waves-effect waves-light delete"
                                            onclick="deleteConfirmation({{ $b->id_akun_biaya }})">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
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
    <script>
        function deleteConfirmation(itemId) {
            var form = document.getElementById('deleteForm' + itemId);

            Swal.fire({
                title: 'Are you sure?',
                text: 'Are you sure to delete this item?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!'
            }).then((result) => {
                if (result.value) {
                    form.submit();
                }
            });
        }

        document.querySelectorAll('.delete').forEach(item => {
            item.addEventListener('click', event => {
                event.preventDefault();

                var form = item.parentElement.querySelector('form');
                var itemId = form.id.replace('deleteForm', '');

                deleteConfirmation(itemId);
            });
        });

        $(document).ready(function() {
            $('#crudTable').DataTable({
                "order" : [[0, "desc"]]
            });
        });

        var urlParams = new URLSearchParams(window.location.search);
        var successParam = urlParams.get('success');

        if (successParam === 'true') {
            Swal.fire({
                title: 'Success!',
                text: 'The item has been deleted successfully.',
                type: 'success'
            }).then(() => {
                window.history.back();
            });
        }
    </script>
@endpush
