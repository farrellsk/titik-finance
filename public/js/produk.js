// Mendapatkan semua elemen dengan ID "uang"
var uangElements = document.querySelectorAll('[id="nilai_produk"]');

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

//select kategori produk
$(document).ready(function() {
$('.katprod').select2({
    ajax: {
        url: '/kat-produk-select-2',
        dataType: 'json',
        processResults: function(kpt) {
                var datakaprod = kpt[0].map(function(katpro) {
                    return {
                        'id': katpro.id,
                        'text': katpro.nama_produk_kategori
                    };
                });
    
                return {
                    results: datakaprod
                };
        }
    }
});
});


