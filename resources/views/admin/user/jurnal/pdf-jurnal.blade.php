<!DOCTYPE html>
<html>

<head>
    <title>Unduh Jurnal</title>
</head>

<body>
    <div class="content-body" style="width: 100%; min-height:600px; overflow: auto;  ">
        <div class="navbar" style="width: 100%; height: 100px; background-color: #919191">
            <table border="0" style="width: 100%; height:100%; color:white; padding-top: 10px">
                <tr>
                    <td style="width: 60px;">
                        <img src="data:image/png;base64,{{ $imageData ? $imageData : "" }}"
                        style="height: 80px; width: 100px;">
                    </td>
                    <td style="padding-left: 50%; font-size: 30px; vertical-align: bottom;font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;">
                        Laporan Jurnal
                    </td>
                </tr>
            </table>
        </div>

        {{-- detail pengeluaran --}}
        <div class="info-table2" style="width: 100%;"
        >
            <table border="0"
                style="width: 100%;font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; margin: auto;
            padding: 30px;
            font-size: 12px;
            line-height: 18px;"
                cellspacing="0" cellpadding="0">
                <thead>
                    <tr style="border: none; text-align:center;">
                        <th>Tanggal</b></th>
                        <th>Akun</b></th>
                        <th>Keterangan</b></th>
                        <th>Debit (IDR)</b></th>
                        <th>Kredit (IDR)</b></th>
                        <th>Saldo</b></th>
                        <th>Status</b></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($juntrak as $item)
                    <tr>
                        <td style="border: 1px solid #727171; padding-left:10px">{{ $item['tanggal'] }}</td>
                        <td style="border: 1px solid #727171; padding-left:10px">{!! $item['kontak'] !!}</td>
                        <td style="border: 1px solid #727171; padding-left:10px">{!! $item['deskripsi'] !!}</td>
                        <td style="border: 1px solid #727171; padding-left:10px; padding-right:10px">{{ $item['terima'] }}</td>
                        <td style="border: 1px solid #727171; padding-left:10px; padding-right:10px">{{ $item['kirim'] }}</td>
                        <td style="border: 1px solid #727171; padding-left:10px; padding-right:10px">{{ $item['saldo'] }}</td>
                        <td style="border: 1px solid #727171; padding-left:10px; padding-right:10px">{{ $item['status'] }}</td>
                        <td></td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        @if ($jenis == "mutasi")
                        <td colspan="2" style="border: 1px solid #727171; padding-left:10px;">Total Saldo saat ini</td>
                        <td colspan="7" style="border: 1px solid #727171; padding-left:10px; text-align: right;padding-right: 10px"><b>{{ $item['saldo'] }}</b></td>
                        @elseif ($jenis == "pengeluaran")
                        <td style="border: 1px solid #727171; padding-left:10px;">Total Pengeluaran</td>
                        <td colspan="7" style="border: 1px solid #727171; padding-left:10px; text-align: right; padding-right: 10px"><b>{{ $item['total'] }}</b></td>
                        @else
                        <td></td>
                        <td style="border: 1px solid #727171; padding-left:10px;">Total Pemasukan</td>
                        <td colspan="7" style="border: 1px solid #727171; padding-left:10px; text-align: right; padding-right: 10px"><b>{{ $item['total'] }}</b></td>
                        @endif

                    </tr>
                </tfoot>
            </table>
        </div>
        {{-- akhir detail pengeluaran --}}
        <div class="table-info2" style="padding-top: 10px; width: 100%; height: 100px; margin-top: 30px">
            <table border="0" style="width: 100%; height: 100%; font-size: 14px; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;">
                <tr>
                    <td style="padding-left: 12px">
                        <b>{{$setting ? $setting->nama_perusahaan : "" }}</b><br>
                        <small>
                           {{ $setting ? $setting->alamat : ""}} <br>
                            Kecamatan {{ $setting ? $setting->kecamatan : "" }} Kota {{ $setting ? $setting->kota : "" }} <br>
                            Telp.{{ $setting ? $setting->no_hp : "" }} | Email: {{ $setting ? $setting->email : "" }} <br>
                        </small>
                    </td>
                </tr>
            </table>

        </div>

    </div>
</body>

</html>
