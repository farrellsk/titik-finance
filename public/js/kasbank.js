//select kategori produk
$(document).ready(function() {
    $('.namaKasbank').select2({
        placeholder: 'Pilih Kas / Bank',
        ajax: {
            url: '/nama-kasbank-select-2',
            dataType: 'json',
            processResults: function(namaKB) {
                    var dataNamaKB = namaKB[0].map(function(namaKasbank) {
                        return {
                            'id': namaKasbank.id_kas,
                            'text': namaKasbank.nama_akun
                        };
                    });
        
                    return {
                        results: dataNamaKB
                    };
            }
        }
    });
});


//saldo change
$(document).ready(function() {
    // Function to format number as Rupiah
    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(number);
    }

    // Set up the event handler
    $('.akun_asal').on('change', function() {
        var selectedId = $(this).val();

        // Send AJAX request to get saldo akun
        $.ajax({
            url: '/get-saldo-akun/' + selectedId, // Sesuaikan dengan URL endpoint Anda
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    var saldo = response.data.saldo_akhir;
                    $('.saldo-akhir').text(formatRupiah(saldo)).css('color', 'blue');
                } else {
                    $('.saldo-akhir').text('Tidak ada saldo').css('color', 'red');
                }
            },
            error: function() {
                $('.saldo-akhir').text('Gagal mengambil data saldo').css('color', 'red');
            }
        });
    });

    // Trigger the change event manually to handle the initial state
    $('.akun_asal').trigger('change');
});


$(document).ready(function () {
    $('.bank-fields').hide();

    $('#payment-method').change(function () {
        var selectedOption = $(this).val();

        if (selectedOption === 'Bank') {
            $('.bank-fields').show();
        } else {
            $('.bank-fields').hide();
        }
    });
});

//edit
$(document).ready(function () {
    var selectedOption = $('#payment-method').val();
    if (selectedOption === 'Bank') {
        $('.bank-fields').show();
    }
});

document.getElementById('payment-method').addEventListener('change', function () {
    var selectedValue = this.value;

    if (selectedValue === 'Kas') {
        document.querySelector('.bank-fields input[name="nama_pemilik"]').value = '';
        document.querySelector('.bank-fields input[name="no_rekening"]').value = '';
    }
});



//ubah format
function formatCurrency(input) {
    var value = input.value;
    var numericValue = value.replace(/[^\d]/g, '');

    if (!isNaN(numericValue)) {
        input.value = formatRupiah(parseInt(numericValue,10), 'Rp. ');
    }
}

function formatRupiah(angka, prefix) {
    var number_string = angka.toString().replace(/[^,\d]/g, ''),
        split = number_string.split(','),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
}


// Mendapatkan semua elemen dengan ID "uang"
var uangElements = document.querySelectorAll('[id="uang"]');

// Menambahkan event listener untuk setiap elemen
uangElements.forEach(function (elem) {
    elem.addEventListener('input', function (e) {
        // Memanggil fungsi uangformat dan mengatur nilai elemen
        elem.value = uangformat(this.value, 'Rp. ');
    });
});

function uangformat(ank, rp) {
    var number_string = ank.replace(/[^,\d]/g, '').toString(),
        split = number_string.split(','),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    return rp == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
}


//kasbank.js
//ubah format
var harga = document.getElementById('harga');
harga.addEventListener('keyup', function (e) {
    harga.value = parseInt(this.value.replace(/[^\d]/g, ''), 10);
    harga.value = formatRupiah(this.value, 'Rp. ');
});

function formatRupiah(angka, prefix) {
    var number_string = angka.toString().replace(/[^,\d]/g, ''),
        split = number_string.split(','),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
}

