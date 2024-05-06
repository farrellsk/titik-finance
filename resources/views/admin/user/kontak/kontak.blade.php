@extends('layouts.app', [
    'title' => 'Kontak',
    'breadcrumbs' => ['Kontak', 'List'],
])

@section('content')
        <div class="card">
            <div class="card-content">
                <div class="card-body">
                    <div class="d-flex juftify-content-between">
                        <a href="{{ route ('kontak-tambah') }}" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Kontak
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
                            <h5 class="modal-title" id="filterModalLabel">Filter Kontak</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                    <div class="modal-body">
                        <form method="get" action="{{ route('users.kontak') }}">
                            <div class="form-group">
                                <label for="nama_kontak">Nama Kontak:</label>
                                <input type="text" name="nama_kontak" class="form-control" value="{{ request('nama_kontak') }}">
                            </div>
                            <div class="form-group">
                                <label for="no_telp">No. Hp / Telepon:</label>
                                <input type="number" name="no_telp" class="form-control" value="{{ request('no_telp') }}">
                            </div>
                            <div class="form-group">
                                <label for="tipe_kontak">Tipe Kontak:</label>
                                <br>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="pelanggan" name="tipe_kontak" class="form-check-input" value="Pelanggan" {{ request('tipe_kontak') == 'Pelanggan' ? 'checked' : '' }}>
                                    <label for="pelanggan" class="form-check-label">pelanggan</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="karyawan" name="tipe_kontak" class="form-check-input" value="Karyawan" {{ request('tipe_kontak') == 'Karyawan' ? 'checked' : '' }}>
                                    <label for="karyawan" class="form-check-label">karyawan</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="suplier" name="tipe_kontak" class="form-check-input" value="Suplier" {{ request('tipe_kontak') == 'Suplier' ? 'checked' : '' }}>
                                    <label for="suplier" class="form-check-label">suplier</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="lainnya" name="tipe_kontak" class="form-check-input" value="Lainnya" {{ request('tipe_kontak') == 'Lainnya' ? 'checked' : '' }}>
                                    <label for="lainnya" class="form-check-label">lainnya</label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-info">Filter</button>
                            <a href="{{ route('users.kontak') }}" class="btn btn-danger">Reset</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
                    <div class="table-responsive">
                        <table class="table table-hover-animation nowrap scroll-horizontal-vertical" id="crudTable">
                            <thead>
                                <tr>
                                    <th>Kode Kontak</th>
                                    <th>Nama Kontak</th>
                                    <th>Tipe Kontak</th>
                                    <th>No. Telepon</th>
                                    <th>Alamat</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($t_kontak as $item)
                                    <tr>
                                        <td>{{ $item->kode_kontak }}</td>
                                        <td>{{ $item->nama_kontak }}</td>
                                        <td>{{ $item->tipe_kontak }}</td>
                                        <td>{{ $item->no_telp }}</td>
                                        <td>{{ $item->alamat }}</td>
                                        <td> <a href="/edit/kontak/{{ $item->id_kontak }}"
                                                class="btn btn-icon btn-sm btn-success waves-effect waves-light"
                                                style="margin-right: 5px;">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <form method="POST"
                                                action="{{ route('kontak-delete', ['id' => $item->id_kontak]) }}"
                                                id="deleteForm{{ $item->id_kontak }}" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            <button title="Delete"
                                                class="btn btn-icon btn-sm btn-danger waves-effect waves-light delete"
                                                onclick="deleteConfirmation({{ $item->id_kontak }})">
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
                    // Your DataTables initialization options
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
