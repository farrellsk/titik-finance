@extends('layouts.app', [
    'title' => 'Histori Mutasi',
    'breadcrumbs' => ['Histori Mutasi', 'List'],
])

@section('content')
    <div class="card">
        <div class="card-content">
            <div class="card-body">
                <div class="d-flex juftify-content-between">
                    <a href="{{ route('mutasi-tambah') }}" class="btn btn-primary">
                        <i class="fa fa-plus"></i> Mutasi
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover-animation nowrap scroll-horizontal-vertical" id="crudTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Akun Asal</th>
                                <th>Akun Tujuan</th>
                                <th>Jumlah</th>
                                <th>Biaya Layanan</th>
                                <th>Keterangan</th>
                                <th>Lampiran</th>
                                <th>Waktu</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($t_mutasi as $mts)
                                <tr>
                                    <td>{{ $mts->id }}</td>
                                    <td>{{ $mts->akunAsal->nama_akun }}</td>
                                    <td>{{ $mts->akunTujuan->nama_akun }}</td>
                                    <td>@currency($mts->jumlah_mutasi)</td>
                                    <td>@currency($mts->biaya_layanan)</td>
                                    <td>{{ $mts->keterangan }}</td>
                                    <td>
                                        @if ($mts->lampiran)
                                            <a href="{{ asset('/image/lampiran/' . $mts->lampiran) }}" target="_blank"> Lihat Lampiran</a>
                                        @else
                                            Tidak ada lampiran
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($mts->waktu)->format('Y-m-d H:i:s') }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('mutasi-delete', ['id' => $mts->id]) }}"
                                            id="deleteForm{{ $mts->id }}" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>

                                        <button title="Delete"
                                            class="btn btn-icon btn-sm btn-danger waves-effect waves-light delete"
                                            onclick="deleteConfirmation({{ $mts->id }})">
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
                "order": [
                    [0, "desc"]
                ]
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
