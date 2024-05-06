@extends('layouts.app', [
    'title' => 'Jurnal',
    'breadcrumbs' => ['Jurnal'],
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
                            <th>Akun</th>
                            <th>Produk</th>
                            <th>Keterangan</th>
                            <th>Debit / Pembelian</th>
                            <th>Kredit / Penjualan</th>
                            <th>Saldo (dalam IDR)</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($juntrak as $j)
                        <tr>
                            <td>
                                {{ \Carbon\Carbon::parse($j['tanggal'])->format('d/m/Y') }}<br>
                                <span style="font-size: 12px; font-weight: bold;">
                                    {{ \Carbon\Carbon::parse($j['tanggal'])->format('H:i:s') }}
                                </span>
                            </td>                            
                            <td>{!! $j['kontak'] !!}</td>
                            <td>{{ $j['produk'] }}</td>
                            <td>{!! $j['deskripsi'] !!}</td>
                            <td>{{ $j['kirim'] }}</td>
                            <td>{{ $j['terima'] }}</td>
                            <td>{!! $j['saldo'] !!}</td>
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
        "order": [[0, "desc"]],
        "columnDefs": [
            { "type": "date", "targets": 1 }
        ]
    });
});

        </script>
    @endpush
