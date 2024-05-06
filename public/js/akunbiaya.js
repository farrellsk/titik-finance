//INI SELECT2

$(document).ready(function() {
    $('#nama_penerima').select2({
        ajax: {
            url: '/fetch-kontak-data',
            dataType: 'json',
            processResults: function(data) {
                console.log(data);
                var formattedData = data[0].map(function(item) {
                    return {
                        'id': item.nama_kontak,
                        'text': item.nama_kontak
                    };
                });


                return {
                    results: formattedData
                };
            }
        }
    });
});

//INI SELECT2 KATEGORI
$(document).ready(function() {
    $('#nama_kategoriselect').select2({
        ajax: {
            url: '/kategori-select-2',
            dataType: 'json',
            processResults: function(gori) {
                    var datakategori = gori[0].map(function(kat) {
                        return {
                            'id': kat.nama_kategori,
                            'text': kat.nama_kategori
                        };
                    });
        
                    return {
                        results: datakategori
                    };
            }
        }
    });
});

//kasih tau saldo
     $(document).ready(function() {
        // Function to format number as Rupiah
        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(number);
        }

        // Set up the event handler
        $('#payment-method').on('change', function() {
            var selectedOption = $(this).find(':selected');
            var saldo = selectedOption.data('saldo_akhir');

            // Update the saldo text and set the color to green
            $('#saldo-text').text('Saldo: ' + formatRupiah(saldo)).css('color', 'green');
        });

        // Trigger the change event manually to handle the initial state
        $('#payment-method').trigger('change');
    });

// maaf mas saldonya kelebihan
    document.getElementById('harga').addEventListener('input', function() {
        var jumlah = parseInt(this.value.replace(/\D/g, '')) || 0;
        var saldoAwal = parseInt(document.getElementById('payment-method').options[document.getElementById('payment-method').selectedIndex].getAttribute('data-saldo_akhir')) || 0;

        if (jumlah > saldoAwal) {
            document.getElementById('saldo-error').innerText = 'Saldo Tidak Mencukupi';
        } else {
            document.getElementById('saldo-error').innerText = '';
        }
    });

    // Memperbarui teks saldo saat pengguna memilih metode pembayaran
    document.getElementById('payment-method').addEventListener('change', function() {
        var saldoAwal = parseInt(this.options[this.selectedIndex].getAttribute('data-saldo_akhir')) || 0;
        document.getElementById('saldo-text').innerText = 'Saldo Awal: Rp. ' + saldoAwal.toLocaleString('id-ID');
    });