@extends('layouts.app', [
    'title' => 'Kategori Biaya',
    'breadcrumbs' => [
        'Kategori Biaya','List'],
]) 

@section('content')
<div class="card">
    <div class="card-content">
        <div class="card-body">
            <div class="d-flex juftify-content-between">
                <a href="{{ route ('kategori-create') }}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Kategori
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
                        <h5 class="modal-title" id="filterModalLabel">Filter Kategori Biaya</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    
                    <div class="modal-body">
                        <form method="get" action="{{ route('kategori-biaya') }}">
                            <div class="form-group">
                                <label for="nama_kategori">Nama Kategori:</label>
                                <input type="text" name="nama_kategori" class="form-control" value="{{ request('nama_kategori') }}">
                            </div>
                            <div class="form-row"> <!---buat memperpendek-->
                                <div class="form-group col-md-6">
                                    <label for="saldo_min">Saldo Min:</label>
                                    <input type="number" name="saldo_min" class="form-control" value="{{ request('saldo_min') }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="saldo_max">Saldo Max:</label>
                                    <input type="number" name="saldo_max" class="form-control" value="{{ request('saldo_max') }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="status">Status:</label>
                                <select name="status" class="form-control">
                                    <option value="">Semua</option>
                                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="non-aktif" {{ request('status') == 'non-aktif' ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-info">Filter</button>
                            <a href="{{ route('kategori-biaya') }}" class="btn btn-danger">Reset</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!--modal history-->
        
        @foreach ($historyData as $b)
        <div id="historyModal{{$b->id_kategori_biaya}}" class="modal fade" data-target="modal">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <b class="modal-title">Riwayat Akun Biaya - {{ $b->kategori->nama_kategori}}</b>
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                  </button>
                </div>
                <div class="modal-body">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>Jumlah</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach($historyData->where('id_kategori_biaya', $b->id_kategori_biaya) as $h => $hs)
                        <tr>
                            <td>{{ $hs->created_at->format('d-m-Y') }}</td>
                            <td>{{ $hs->nama_akun_biaya }}</td>
                            <td> @currency($hs->jumlah)</td>
                        </tr>
                    @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          @endforeach

          <!--beres-->

            <div class="table-responsive">
                <table class="table table-hover-animation nowrap scroll-horizontal-vertical" id="crudTable">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nama Kategori</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Action</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($t_kategoribiaya as $item)
                        <tr>
                            <td>{{ $item->id_kategori_biaya }}</td>
                            <td>{{ $item->nama_kategori }}</td>
                            <td>Rp. {{ number_format($item->jumlah) }}</td>
                            <td>{{ $item->status }}</td>
                            <td> 
                            <a href="/kategori-edit/{{$item->id_kategori_biaya}}" class="btn btn-icon btn-sm btn-success waves-effect waves-light">
                                <i class="fa fa-edit"></i>
                            </a>
                            
                            <a href="#historyModal{{$item->id_kategori_biaya}}"  data-toggle="modal" class="btn btn-icon btn-sm btn-warning waves-effect waves-light">
                                <i class="fa fa-eye"></i>
                            </a>
                            
                            <form method="POST" action="{{ route('delete-kategori', ['id' => $item->id_kategori_biaya]) }}" id="deleteForm{{$item->id_kategori_biaya}}" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form> 

                            <button title="Delete" class="btn btn-icon btn-sm btn-danger waves-effect waves-light delete" onclick="deleteConfirmation({{ $item->id_kategori_biaya }})">
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
