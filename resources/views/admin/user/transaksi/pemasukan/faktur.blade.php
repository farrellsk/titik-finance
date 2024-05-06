<!DOCTYPE html>
<html>

<head>
    <title>Faktur Penjualan</title>
</head>

<body>
    <div class="content-body" style="width: 100%; height:600px;  ">
        <div class="navbar" style="width: 100%; height: 100px; background-color: #919191">
            <table border="0" style="width: 100%; height:100%; color:white; padding-top: 10px">
                <tr>
                    <td style="width: 60px;">
                        <img src="data:image/png;base64,{{ $imageData ? $imageData : "" }}"
                        style="height: 80px; width: 100px;">
                    </td>
                    <td style="padding-left: 70%; font-size: 50px; vertical-align: bottom;font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;">
                        Faktur
                    </td>
                </tr>
            </table>
        </div>


          <!-- detail informasi -->
          <div class="detail-content" style="display: flex; height: 100px; width: 100%; flex-wrap: nowrap; margin-top:10px">
            <!-- informasi alamat -->
            <table border="0" style="width: 100%; height: 100%; font-size: 14px; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;">
                <tr>
                    <td style="padding-left: 12px">
                        <b>{{ $setting ? $setting->nama_perusahaan : "" }}</b><br>
                        <small>
                            {{ $setting ? $setting->alamat : "" }} <br>
                            Kecamatan {{ $setting ? $setting->kecamatan : "" }} Kota {{ $setting ? $setting->kota : "" }} <br>
                            Telp.{{ $setting ? $setting->no_hp : "" }} | Email: {{ $setting ? $setting->email : "" }} <br>
                        </small>
                    </td>
                    <td style="padding-left: 15%;"><!-- Perhatikan perubahan di sini -->
                        <table border="0" style="width:100%; font-size: 13px; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;">
                            <tr>
                                <td>Date:</td>
                                <td>{{ $pemasukan[5] }}</td>
                            </tr>
                            <tr>
                                <td>Invoice #:</td>
                                <td>{{ $pemasukan[0] }}</td>
                            </tr>
                            <tr>
                                <td>Customer ID:</td>
                                <td>{{ $pemasukan[6] }}</td>
                            </tr>
                            <tr>
                                <td>Expiration Date:</td>
                                <td>{{ $pemasukan[8] }}</td>
                            </tr>

                        </table>
                    </td>
                </tr>
            </table>


            <!-- akhir informasi faktur -->
        </div>
        <!-- akhir detail informasi -->

        {{-- nama custumer --}}
        <div class="to-customer" style="display: flex; width: 100%; height: 30px; padding-left: 12px">
            <table border="0" style="font-size: 12px; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;">
                <tr>
                    <td style="width: 100px">To:</td>
                    <td>{{ $pemasukan[11] }}</td>
                </tr>
            </table>
        </div>
        {{-- akhir nama custumer --}}

        {{-- detail faktur pemasukan --}}
        <div class="info-table1"
            style="width: 100%; height: 70px; margin-top:15px">
            <table border="0"
                style="width: 100%;font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; margin: auto;
            padding: 30px;
            font-size: 12px;
            line-height: 18px;"
                cellspacing="0" cellpadding="0">
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
                    <td style="border: 1px solid #727171; padding-left:10px; padding-left:10px;">{{ $pemasukan[9] }}</td>
                </tr>
            </table>
        </div>
        <div class="info-table2" style="width: 100%; height: 310px;">
            <table border="0"
                style="width: 100%;font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; margin: auto;
            padding: 30px;
            font-size: 12px;
            line-height: 18px;"
                cellspacing="0" cellpadding="0">
                <thead>
                    <tr style="border: none; text-align:center;">
                        <th>Qty</b></th>
                        <th>Description</b></th>
                        <th>Unit Price</b></th>
                        <th>Line Total</b></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transItem as $item)
                    <tr>
                        <td style="border: 1px solid #727171; padding-left:10px">{{ $item->qty }} buah</td>
                        <td style="border: 1px solid #727171; padding-left:10px">{{ $item->produk->nama_produk }}</td>
                        <td style="border: 1px solid #727171; padding-left:10px">@currency($item->amount )</td>
                        <td style="border: 1px solid #727171; padding-left:10px">@currency($item->total )</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" rowspan="4">
                            <div class="payment-text" style="margin-left: 10px; margin-top: 20px">
                                {{ $pemasukan[2] }} <br>
                                NAMA BANK: {{ $pemasukan[7] }} <br>
                                NOMOR AKUN BANK :{{ $pemasukan[10] }} <br>
                                ATAS NAMA : {{ $setting ? $setting->nama_perusahaan : "" }}
                            </div>
                        </td>
                        <td style="border: 1px solid #727171; padding-left:10px;">Subtotal</td>
                        <td style="border: 1px solid #727171; padding-left:10px;">@currency($total)</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #727171; padding-left:10px;">Diskon</td>
                        <td style="border: 1px solid #727171; padding-left:10px;"></td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #727171; padding-left:10px;">Total</td>
                        <td style="border: 1px solid #727171; padding-left:10px;"><b>@currency($total)</b></td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>


            <div class="text-thx" style="margin-top: 50px">
                <center><b>Thank you for your business!</b></center>
            </div>

        </div>
        {{-- akhir detail faktur pemasukan --}}

    </div>
</body>

</html>
