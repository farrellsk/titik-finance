@extends('layouts.app', [
    'title' => 'Penjualan',
    'breadcrumbs' => ['Transaksi', 'Penjualan'],
])
@section('content')
    <div class="card">
        <div class="card-content">
            <div class="card-body">
                <a href="{{ route('pemasukan.create') }}" class="btn btn-primary waves-effect waves-light"><i
                        class="fa fa-plus"></i> Penjualan</a>
                <div class="table-responsive">
                    <table class="table table-hover-animation nowrap scroll-horizontal-vertical" id="crudTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Pelanggan</th>
                                <th>Metode Pembayaran</th>
                                <th>Dokumen</th>
                                <th>Tgl. Jatuh Tempo</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transaksi as $t)
                                <tr>
                                    <td>{{ $t->id }}</td>
                                    <td>{{ date('d-m-Y', strtotime($t->tanggal)) }}</td>
                                    <td>{{ $t->contact ? $t->contact->nama_kontak : '-' }}</td>
                                    <td>{{ $t->kasbank->payment }} - {{ $t->kasbank->nama_akun }}</td>
                                    <td>
                                        @if ($t->hasManyTrans_Attachment)
                                            @foreach ($t->hasManyTrans_Attachment as $attachment)
                                                <a href="{{ asset('/uploads/' . $attachment->url) }}" target="_blank">Lihat
                                                    Dokumen</a><br>
                                            @endforeach
                                        @else
                                            Tidak ada Dokumen
                                        @endif
                                    </td>
                                    <td>{{ $t->do_date ? date('d-m-Y', strtotime($t->do_date)) : '-' }}</td>
                                    <td>{{ $t->total }}</td>
                                    @if ($t->status == 'Success')
                                        <td><span class="badge rounded-pill bg-success">{{ $t->status }}</span></td>
                                    @elseif ($t->status == 'Pending')
                                        <td><span class="badge rounded-pill bg-secondary">{{ $t->status }}</span></td>
                                    @elseif ($t->status == 'Reject')
                                        <td><span class="badge rounded-pill bg-danger">{{ $t->status }}</span></td>
                                    @elseif ($t->status == 'Cancel')
                                        <td><span class="badge rounded-pill bg-danger">{{ $t->status }}</span></td>
                                    @endif
                                    <td>
                                        <a href="#detail{{ $t->id }}" data-toggle="modal"
                                            class="btn btn-icon btn-sm btn-warning waves-effect waves-light">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        @if ($t->status == 'Pending')
                                        <a href="/edit/pemasukan/{{ $t->id }}"
                                            class="btn btn-icon btn-sm btn-success waves-effect waves-light">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        @endif
                                        <form method="POST" action="{{ route('pemasukan.delete', ['id' => $t->id]) }}"
                                            id="deleteForm{{ $t->id }}" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <button title="Delete"
                                            class="btn btn-icon btn-sm btn-danger waves-effect waves-light delete"
                                            onclick="deleteConfirmation({{ $t->id }})">
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

    @foreach ($transItem as $t)
    <div id="detail{{ $t->id }}" class="modal fade show" data-target="modal"
        aria-labelledby="exampleModalCenteredScrollableTitle" role="dialog">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="header-container"
                        style="display: flex; justify-content: space-between; align-items: center;">
                        <b class="modal-title" style="font-size: 20px;">Data Penjualan</b>
                        @if ($t->status == 'Success')
                            <div>
                                <a href="{{ route('data.pemasukan', ['id_trans' => $t->id]) }}"
                                    class="btn btn-lg btn-print" style="margin-right: 1px;" target="_blank">
                                    <i class="feather icon-printer"><br><small>Faktur</small></i></a>
                                <a href="{{ route('invoice.pemasukan', ['id' => $t->id]) }}"
                                    class="btn btn-lg btn-print" target="_blank">
                                    <i class="feather icon-printer"><br><small>Invoice</small></i>
                                </a>
                            </div>
                        @else
                            <a href="{{ route('data.pemasukan', ['id_trans' => $t->id]) }}"
                                class="btn btn-lg btn-print" target="_blank">
                                <i class="feather icon-printer"><br><small>faktur</small></i></a>
                        @endif
                    </div>

                    <div class="content-body" style="width: 100%; min-height: 600px; border: 1px solid #080707; overflow: auto; ">
                        <div class="navbar" style="width: 100%; height: 65px; background-color: #919191">
                            <div class="logo">
                                <img src="{{ $setting ? asset('images/setting/logo/'.$setting->logo) : "" }}" style="width: 60px; height: 50px" />
                            </div>

                        </div>

                        {{-- detail informasi --}}
                        <div class="detail-content" style="display: flex; height: 120px; width:100%; ">
                            {{-- informasi alamat --}}
                            <div class="text-detail"
                                style="flex: 0 0 400px; height: 100%; padding-top:10px; padding-left:10px;">
                                <b>{{ $setting ? $setting->nama_perusahaan : "" }}</b><br>
                                <small>
                                    {{ $setting ? $setting->alamat : "" }} <br>
                                    Kecamatan {{$setting ? $setting->kecamatan : ""}} Kota {{ $setting ? $setting->kota : "" }} <br>
                                    Telp.{{ $setting ? $setting->no_hp : "" }} | Email: {{ $setting ? $setting->email : "" }} <br>
                                </small>
                            </div>
                            {{-- akhir informasi alamat --}}

                            {{-- informasi faktur --}}
                            <div class="table-det"
                                style="flex-grow: 1; height: 100%; padding-left: 10px; display: flex; align-items: center;">
                                <table border="0" style="width: 100%; font-size: 12px">
                                    <tr>
                                        <td>Date:</td>
                                        <td>{{ \Carbon\Carbon::parse($t->tanggal)->format('F d, Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Invoice #:</td>
                                        <td>{{ $t->id }}</td>
                                    </tr>
                                    <tr>
                                        <td>Custumer ID:</td>
                                        <td>{{ $t->contact_id }}</td>
                                    </tr>
                                    <tr>
                                        <td>Expiration Date:</td>
                                        <td>{{ \Carbon\Carbon::parse($t->do_date)->format('F d, Y') }}</td>
                                    </tr>
                                </table>
                            </div>
                            {{-- akhir informasi faktur --}}
                        </div>
                        {{-- akhir detail informasi --}}

                        {{-- nama custumer --}}
                        <div class="to-customer" style="display: flex; width: 100%; height: 30px;  padding-left: 12px">
                            <div class="to-text"
                                style="flex: 0 0 100px; height:100%; display: flex; font-size: 12px; align-items: center;">
                                To:
                            </div>
                            <div class="customer-text"
                                style="flex-grow: 1; height:100%; display:flex; align-items: center; padding-left: 10px; font-size: 12px">
                                {{ $t->contact->nama_kontak }}
                            </div>
                        </div>
                        {{-- akhir nama custumer --}}

                        {{-- detail faktur pengeluaran --}}
                        <div class="info-table1"
                            style="width: 100%; padding-left: 10px; padding-right: 10px; margin-top:20px; margin-bottom: 15px;">
                            <table border="0"
                                style="width: 100%;font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; margin: auto;
                            padding: 30px;
                            font-size: 12px;
                            line-height: 18px;">
                                <tr style="border:none; text-align: center">
                                    <th>Tags</th>
                                    <th>Cara Pengiriman</th>
                                    <th>Terms</th>
                                    <th>Jatuh Tempo</th>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid #727171; padding-left:10px; padding-left:10px;"></td>
                                    <td style="border: 1px solid #727171; padding-left:10px; padding-left:10px;"></td>
                                    <td style="border: 1px solid #727171; padding-left:10px; padding-left:10px;">-</td>
                                    <td style="border: 1px solid #727171; padding-left:10px; padding-left:10px;">
                                        {{ \Carbon\Carbon::parse($t->do_date)->format('d/m/y') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="info-table2"
                            style="width: 100%; padding-left: 10px; padding-right: 10px">
                            <table border="0"
                                style="width: 100%;font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; margin: auto;
                            padding: 30px;
                            font-size: 12px;
                            line-height: 18px;">
                                <thead>
                                    <tr style="border: none; text-align:center;">
                                        <th>Qty</b></th>
                                        <th>Description</b></th>
                                        <th>Unit Price</b></th>
                                        <th>Line Total</b></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($t->hasManyTransaksi as $item)
                                        <tr>
                                            <td style="border: 1px solid #727171; padding-left:10px">
                                                {{ $item->qty }} Buah</td>
                                            <td style="border: 1px solid #727171; padding-left:10px">
                                                {{ $item->produk->nama_produk }}</td>
                                            <td style="border: 1px solid #727171; padding-left:10px">@currency($item->amount)
                                            </td>
                                            <td style="border: 1px solid #727171; padding-left:10px">@currency($item->total)
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2" rowspan="4">
                                            <div class="payment-text" style="margin-left: 10px; margin-top: 20px">
                                                {{ $t->kasbank->payment }} <br>
                                                NAMA BANK: {{ $t->kasbank->nama_akun }} <br>
                                                NOMOR AKUN BANK : {{ $t->kasbank->no_rekening }} <br>
                                                ATAS NAMA : {{ $setting ? $setting->nama_perusahaan : "" }}
                                            </div>
                                        </td>
                                        <td style="border: 1px solid #727171; padding-left:10px;">Subtotal</td>
                                        <td style="border: 1px solid #727171; padding-left:10px;">@currency($t->hasManyTransaksi->sum('total'))</td>
                                    </tr>
                                    <tr>
                                        <td style="border: 1px solid #727171; padding-left:10px;">Diskon</td>
                                        <td style="border: 1px solid #727171; padding-left:10px;"></td>
                                    </tr>
                                    <tr>
                                        <td style="border: 1px solid #727171; padding-left:10px;">Total</td>
                                        <td style="border: 1px solid #727171; padding-left:10px;">
                                            <b>@currency($t->hasManyTransaksi->sum('total'))</b></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"></td>
                                    </tr>
                                </tfoot>
                            </table>


                            <div class="text-thx" style="margin-top: 50px; margin-bottom: 30px">
                                <center><b>Thank you for your business!</b></center>
                            </div>

                        </div>
                        {{-- akhir detail faktur pengeluaran --}}

                    </div>
                    {{-- akhir body modal --}}

                </div>
            </div>
        </div>
    </div>
    @endforeach
@endsection
@push('after_styles')
    <link rel="stylesheet" href="{{ asset('app-assets/vendors/css/tables/datatable/datatables.min.css') }}">
@endpush

@push('after_scripts')
    <script>
        $(document).ready(function() {
            @foreach ($transItem as $p)
                $('#detail{{ $p->id_transaksi }}').modal({
                    show: false
                });
            @endforeach
        });
    </script>

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
