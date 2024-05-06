<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice Penjualan</title>
</head>
<body>
    {{-- body invoice --}}
    <div class="body-invoice" style="width: 700px; height:600px;">
        {{-- logo dan detail --}}
        <div class="nav-invoice" style="display: flex; align-items: center; justify-content: space-between; width: 100%; height:100px;">
            <table border="0" style="width:100%; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;">
                <tr>
                    <td>
                        <b>{{ $setting ? $setting->nama_perusahaan : "" }}</b><br>
                        <small>
                            {{$setting ? $setting->alamat : "" }}<br>
                            Kecamatan {{$setting ? $setting->kecamatan : "" }} Kota {{ $setting ? $setting->kota : "" }} <br>
                            {{$setting ? $setting->no_hp : "" }} | Email: {{ $setting ? $setting->email : "" }} <br>
                        </small>
                    </td>
                    <td style="padding-left: 33%">
                        <img src="data:image/png;base64,{{ $imageData }}"
                        style="height: 70px; width: 73px;">
                    </td>
                </tr>
            </table>
        </div>
        {{-- akhir logo dan detail --}}

        <div class="title" style="width:100%; height: 30px; border:1px solid black; text-align: center; padding-top:10px; padding-bottom:10px; font-size: 20px; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;">
            <b>KWITANSI</b>
        </div>

        <div class="info-invoice" style="width: 100%; height: 180px; text-align: center; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;">
         <div class="no" style="margin-top: 10px">No : {{ $pemasukan[0] }}</div>
         <table border="0" style="padding-left: 5%; padding-top:2%; width:80%; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;">
            <tr>
                <td>Telah Terima Dari</td>
                <td>:</td>
                <td>{{ $pemasukan[9] }}</td>
            </tr>
            <tr>
                <td>Banyaknya Uang</td>
                <td>:</td>
                <td>{{ $uang }} rupiah</td>
            </tr>
            <tr>
                <td>Untuk Pembayaran</td>
                <td>:</td>
                <td>Sales Invoice #{{ $pemasukan[0] }}</td>
            </tr>
            <tr>
                <td>Status Pembayaran</td>
                <td>:</td>
                <td>Lunas</td>
            </tr>
         </table>
        </div>

        {{-- total invoice --}}
        <div class="total-body" style="width: 100%; height:40px; ">
            <table border="0" style="width:100%">
                <tr>
                    <td style="border-top: 1px solid black; border-bottom:1px solid black; text-align: center;font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;"><b>JUMLAH</b></td>
                    <td style="background-color: rgb(247, 227, 95);text-align: center;font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;"><b>@currency($total),-</b></td>
                    <td style="text-align: right; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;">{{ $pemasukan[5] }}<br><b>{{ $setting ? $setting->nama_perusahaan : "" }}</b></td>
                </tr>
            </table>
        </div>
        <div class="body-ttd" style="margin-left:60%; width:275px; height:218px;">
        <div class="ttd" style="width:100%; height:160px; padding-left: 100px; padding-top:40px; padding-right:10px"> <img src="data:image/png;base64,{{ $ttd }}"
            style="height: 150px; width: 150px;"></div>
            <div class="nama" style="margin-left:80px; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; text-align: center">{{ $setting ? $setting->nama_ttd : "" }}</div>
        </div>
    </div>
    {{-- Akhir body invoice --}}

</body>
</html>
