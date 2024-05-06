@extends('layouts.app', [
    'title' => 'Produk',
    'breadcrumbs' => ['Produk', 'List']
])

@section('content')
        <div class="card">
            <div class="card-content">
                <div class="card-body">
                    <div class="d-flex juftify-content-between">
                        <a href="{{ route ('produk-tambah') }}" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Produk
                        </a>
            
                        <button type="button" class="btn btn-info ml-auto" data-toggle="modal" data-target="#filterModal">
                            <i class="fa fa-filter"></i> Filter
                        </button>
                    </div>

                    <!---filter produk------>

            <div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="filterModalLabel">Filter Produk</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    
                    <div class="modal-body">
                        <form method="get" action="{{ route('produk') }}">
                            <div class="form-group">
                                <label for="nama_produk">Nama Produk:</label>
                                <input type="text" name="nama_produk" class="form-control" value="{{ request('nama_produk') }}">
                            </div>                            
                            <button type="submit" class="btn btn-info">Filter</button>
                            <a href="{{ route('produk') }}" class="btn btn-danger">Reset</a>
                    </div>
                </div>
            </div>
        </div>
                    <!--------->
                    <div class="table-responsive">
                        <table class="table table-hover-animation nowrap scroll-horizontal-vertical" id="crudTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Produk</th>
                                    <th>Unit</th>        
                                    <th>Harga Jual</th>
                                    <th>Harga Beli</th>
                                    <th>Kode Produk</th>
                                    <th>Kategori Produk</th>
                                    <th>Total Stok</th>
                                    <th>Minimun Stok</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($t_produk as $data)
                                <tr>
                                    <td>{{ $data->id }}</td>
                                    <td>{{ $data->nama_produk }}</td>
                                    <td>{{ $data->unit }}</td>
                                    <td>Rp. {{ number_format($data->harga_jual,0, ',', '.') }}</td>
                                    <td>Rp. {{ number_format($data->harga_beli, 0, ',', '.') }}</td>
                                    <td>{{ $data->kode_produk }}</td>
                                    <td>{{ $data->kategori ? $data->kategori->nama_produk_kategori : '-' }}</td>
                                    <td>{{ $data->total_stok }}</td>
                                    <td>{{ $data->minimun_stok }}</td>
                                    <td>
                                        <a href="{{ route('produk-edit', ['id' => $data->id])}}" class="btn btn-icon btn-sm btn-success waves-effect waves-light" style="margin-right: 5px;">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        
                                        <form method="POST" action="{{ route('delete-produk', ['id' => $data->id]) }}" id="deleteForm{{$data->id}}" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form> 
        
                                        <button title="Delete" class="btn btn-icon btn-sm btn-danger waves-effect waves-light delete" onclick="deleteConfirmation({{ $data->id }})">
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

            console.log("Success Param:", successParam);
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
