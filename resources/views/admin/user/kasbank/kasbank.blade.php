@extends('layouts.app', [
    'title' => 'Kas & Bank',
    'breadcrumbs' => ['Kas & Bank'],
])

@section('content')
    <div class="card">
        <div class="card-content">
            <div class="card-body">
                <a href="/create/kasbank" class="btn btn-primary waves-effect waves-light"><i class="fa fa-plus"></i> Kas</a>
                <div class="table-responsive">
                    <table class="table table-hover-animation nowrap scroll-horizontal-vertical" id="crudTable">
                        <thead>
                            <tr>
                                <th>Kode Kas & Bank</th>
                                <th>Jenis Payment</th>
                                <th>Nama Akun</th>
                                <th>Nama</th>
                                <th>No Rekening</th>
                                <th>Saldo Awal</th>
                                <th>Saldo Akhir</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dt_kas as $kas)
                                <tr class="{{ $kas->saldo_akhir == 0 ? 'table-danger' : '' }}">
                                    <td>{{ $kas->kd_kas }}</td>
                                    <td>{{ $kas->payment }}</td>
                                    <td>{{ $kas->nama_akun }}</td>
                                    <td>{{ $kas->nama_rekening }}</td>
                                    <td>{{ $kas->no_rekening }}</td>
                                    <td>@currency($kas->saldo_awal)</td>
                                    <td>@currency($kas->saldo_akhir)</td>
                                    <td>
                                        <a href="/detailkasbank/{{ $kas->id_kas }}" class="btn btn-icon btn-sm btn-warning waves-effect waves-light">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a href="/edit/kasbank/{{ $kas->id_kas }}"
                                            class="btn btn-icon btn-sm btn-success waves-effect waves-light"
                                            style="margin-right: 0px;">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <form method="POST" action="/delete/kasbank/{{ $kas->id_kas }}"
                                            id="deleteForm{{ $kas->id_kas }}" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <button title="Delete"
                                            class="btn btn-icon btn-sm btn-danger waves-effect waves-light delete"
                                            onclick="deleteConfirmation({{ $kas->id_kas }})">
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
