@extends('layouts.app', [
    'title' => 'Detail Kas Bank',
    'breadcrumbs' => ['Kas Bank', 'Detail Kas Bank'],
])

@section('content')
    <div class="card">
        <div class="card-contect">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover-animation nowrap scroll-horizontal-vertical" id="crudTable">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Kas Bank</th>
                                <th>Tipe</th>
                                <th>Kontak</th>
                                <th>Debit (dalam IDR)</th>
                                <th>Kredit (dalam IDR)</th>
                                <th>saldo (dalam IDR)</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($juntrak as $j)
                                <tr>
                                    <td> {{ $j['tanggal'] }}</td>
                                    <td>{{ $j['kas_bank'] }}</td>
                                    <td>{{ $j['tipe'] }}</td>
                                    <td>{{ $j['kontak'] }}</td>
                                    <td>{{ $j['kirim'] }}</td>
                                    <td>{{ $j['terima'] }}</td>
                                    <td>{{ $j['saldo'] }}</td>
                                    <td><span class="badge rounded-pill bg-success">{{ $j['status'] }}</span></td>
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
        $(document).ready(function() {
            $('#crudTable').DataTable({
                "order": [
                    [0, "desc"]
                ],
            });
        });
    </script>
@endpush
