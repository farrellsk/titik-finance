@extends('layouts.app', [
    'title' => 'Edit Pengeluaran',
    'breadcrumbs' => ['Transaksi', 'Pengeluaran'],
])

@section('content')
    <div class="card">
        <div class="card-content">
            <div class="card-body">
                    <form action="{{ route('transaksi.update', ['id' => $transaksi->id]) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Suplier</label>
                                        <select name="kontak" class="form-control kk1" data-tipe="Suplier" data-id="{{$transaksi->contact_id}}" data-name="{{$transaksi->contact->nama_kontak}}" required>
                                            <option value="">Pilih Suplier</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="hidden" name="item_id[]" value="{{ $transaksi->id }}">
                                        <span class="form-label">Metode Pembayaran</span>
                                        <select name="payment" id="payment-method" class="form-control">
                                            @foreach ($kasbank as $kb)
                                                <option value="{{ $kb->id_kas }}"
                                                    {{ $transaksi->payment_via == $kb->id_kas ? 'selected' : '' }}>
                                                    {{ $kb->payment }} - {{ $kb->nama_akun }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <span class="form-label">Tanggal Transaksi</span>
                                        <input type="date" class="form-control" name="tanggal" value="{{ $transaksi->tanggal }}" required>
                                        <input type="hidden" name="tanggal_hidden" value="{{ $transaksi->tanggal }}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Tanggal Jatuh Tempo</label>
                                        <input type="date" class="form-control" name="tanggal_jatuh_tempo"
                                            value="{{ $transaksi->do_date }}" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Upload File</label>
                                        <input type="file" name="dokumen" class="form-control" id="inputGroupFile02" accept=".jpg,.jpeg,.png,.pdf">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <span class="form-label">Status</label>
                                            <select name="status" id="payment-method" class="form-control">
                                                <option value="Pending"
                                                    {{ $transaksi->status == 'Pending' ? 'selected' : '' }}>Pending
                                                </option>
                                                <option value="Success"
                                                    {{ $transaksi->status == 'Success' ? 'selected' : '' }}>Success
                                                </option>
                                                <option value="Reject"
                                                    {{ $transaksi->status == 'Reject' ? 'selected' : '' }}>Reject
                                                </option>
                                                <option value="Cancel"
                                                    {{ $transaksi->status == 'Cancel' ? 'selected' : '' }}>Cancel
                                                </option>
                                            </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Catatan</label>
                                        <textarea class="form-control" name="note" rows="3">{{$transaksi->notes}}
                                        </textarea>
                                    </div>
                                </div>
                            </div>

                            @if ($transaksi->hasManyTrans_Attachment()->exists())
                                @foreach ($transaksi->hasManyTrans_Attachment()->get() as $attachment)
                                    <div class="form-group">
                                        <div style="display: flex; align-items: center;">
                                            <div class="form-control form-control-sm mb-3"
                                                style="margin-bottom: 0 !important;">
                                                <a
                                                    href="{{ asset('uploads/' . $attachment->url) }}">{{ $attachment->url }}</a>
                                            </div>
                                            <form method="POST" action="{{ route('delete.doc', $attachment->id) }}">
                                                @csrf
                                                <button type="submit" data-id="{{ $attachment->id }}"
                                                    class="btn btn-secondary btn-sm delete-btn">x</button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                            <div class="table-responsive">
                                <table class="table table-hover-animation nowrap scroll-horizontal-vertical" id="crudTable">
                                    <thead>
                                        <tr>
                                            <th> <button type="button" class="btn btn-success btn-sm" id="addRow">+</button></th>
                                            <th>Produk</th>
                                            <th>Harga</th>
                                            <th>Qty</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($transaksi->hasManyTransaksi()->get() as $item)
                                            <tr>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm removeRow">-</button>
                                                </td>

                                                <td>
                                                    <select class="form-control pp1" name="produk[]" required>
                                                        <option value="{{ $item->produk->id }}">{{ $item->produk->nama_produk }}</option>
                                                    </select>
                                                </td>

                                                <td>
                                                    <input type="text" class="form-control" name="nominal[]" id="harga" placeholder="Rp. " onkeyup="formatCurrency(this)" required
                                                        value="@currency($item->amount)" required>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="jumlah[]" value="{{$item->qty}}" required>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="total[]" id="uang"
                                                    placeholder="Rp. " onkeyup="formatCurrency(this)" value="@currency($item->total)" readonly>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>

                            <div class="modal-footer">
                                <a href="/transaksi" class="btn btn-outline-secondary">Kembali</a>
                                <button type="submit" class="btn btn-primary ">Simpan Transaksi</button>
                            </div>


                    </form>
            </div>
        </div>
    </div>
    </div>
    <script>
        $(document).ready(function() {
            $('.kk1').val(selectedOption.id).select2({
                data: [selectedOption],
                ajax: {
                    url: '/peng-kontak-select-2',
                    data: {
                        tipe: tipe
                    },
                    dataType: 'json',
                    processResults: function(pk1) {
                        var datakt1 = pk1[0].map(function(ktk) {
                            return {
                                'id': ktk.id_kontak,
                                'text': ktk.nama_kontak
                            };
                        });

                        return {
                            results: datakt1
                        };
                    }
                }
            });
        });
    </script>
    <script>
        $('.delete-btn').click(function(e) {
            e.preventDefault();
            e.stopPropagation();
            var id = $(this).data('id');
            var url = '/delete/' + id;
            $.ajax({
                url: url,
                type: 'DELETE',
                data: {
                    _token: "{{ csrf_token() }}",
                },
                success: function(response) {
                    location.reload();
                },
                error: function(xhr) {
                    alert('Terjadi kesalahan');
                }
            });
        });
    </script>
    <script>

        document.getElementById('addRow').addEventListener('click', function() {
            var tableBody = document.querySelector('#crudTable tbody');
            var newRow = tableBody.insertRow(tableBody.rows.length);
            var cell1 = newRow.insertCell(0);
            var cell2 = newRow.insertCell(1);
            var cell3 = newRow.insertCell(2);
            var cell4 = newRow.insertCell(3);
            var cell5 = newRow.insertCell(4);

            cell1.innerHTML = ' <button type="button" class="btn btn-danger btn-sm removeRow">-</button>';
            cell2.innerHTML = '<select class="form-control pp1" name="produk[]" required><option value="">Pilih Produk</option></select>';
            cell3.innerHTML = '<input type="text" class="form-control" name="nominal[]" placeholder="Rp. " onkeyup="formatCurrency(this)" required>';
            cell4.innerHTML = '<input type="text" class="form-control" name="jumlah[]" required>';
            cell5.innerHTML = '<input type="text" class="form-control" name="total[]" placeholder="Rp. " readonly>';


            $(cell2).find('.select2-container').remove();
            $(cell3).find('.select2-container').remove();


            $(cell2).find('.pp1').select2({
                ajax: {
                    url: '/peng-produk-select-2',
                    dataType: 'json',
                    processResults: function(pd1) {
                        var datapr1 = pd1[0].map(function(pro1) {
                            return {
                                'id': pro1.id,
                                'text': pro1.nama_produk
                            };
                        });

                        return {
                            results: datapr1
                        };
                    }
                }
            }).on('change', function() {
                var selectedProductId = $(this).val();
                var $this = $(this);

                $.ajax({
                    url: '/harga-jual-produk/' + selectedProductId,
                    type: 'GET',
                    success: function(response) {
                        var harga = response.harga_jual;
                        $this.closest('tr').find('[name="nominal[]"]').val(formatRupiah(harga.toString(), 'Rp. '));
                    },
                    error: function() {
                        alert('Gagal mengambil harga jual.');
                    }
                });
            });

            $(cell3).find('.kk1').select2({
                ajax: {
                    url: '/peng-kontak-select-2',
                    dataType: 'json',
                    processResults: function(pk1) {
                        var datakt1 = pk1[0].map(function(ktk) {
                            return {
                                'id': ktk.id_kontak,
                                'text': ktk.nama_kontak
                            };
                        });

                        return {
                            results: datakt1
                        };
                    }
                }
            });
        });

    $('tbody').on('click', '.removeRow', function(){
            $(this).parent().parent().remove();
        });

                    document.getElementById('crudTable').addEventListener('keyup', function(event) {
                        if (event.target.tagName === 'INPUT' && event.target.name === 'nominal[]') {
                            updateTotal(event.target.closest('tr'));
                        } else if (event.target.tagName === 'INPUT' && event.target.name === 'jumlah[]') {
                            updateTotal(event.target.closest('tr'));
                        } else if (event.target.tagName === 'INPUT' && event.target.name === 'total[]') {

                        }
                    });

                    function updateTotal(row) {

                        var nominal = row.querySelector('input[name="nominal[]"]');
                        var jumlah = row.querySelector('input[name="jumlah[]"]');
                        var total = row.querySelector('input[name="total[]"]');


                        var nominalValue = parseInt(nominal.value.replace(/[^\d]/g, ''), 10) || 0;
                        var jumlahValue = parseInt(jumlah.value, 10) || 0;
                        var totalValue = nominalValue * jumlahValue;


                        total.value = formatRupiah(totalValue, 'Rp. ');


                        var grandTotal = 0;
                        var allRows = document.querySelectorAll('#crudTable tbody tr');
                        allRows.forEach(function(currentRow) {
                            var currentTotal = currentRow.querySelector('input[name="total[]"]');
                            grandTotal += parseInt(currentTotal.value.replace(/[^\d]/g, ''), 10) || 0;
                        });


                        var grandTotalElement = document.getElementById('grandTotal');
                        grandTotalElement.value = formatRupiah(grandTotal, 'Rp. ');
                    }
    </script>
@endsection
