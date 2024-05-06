var harga = document.getElementById('jumlah');
harga.addEventListener('keyup', function(e)
{
    harga.value = formatRupiah(this.value, 'Rp. ');
});

function formatRupiah(angka, prefix)
{
    var number_string = angka.replace(/[^,\d]/g, '').toString(),
        split    = number_string.split(','),
        sisa     = split[0].length % 3,
        rupiah     = split[0].substr(0, sisa),
        ribuan     = split[0].substr(sisa).match(/\d{3}/gi);
        
    if (ribuan) {
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }
    
    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
}

// Mendapatkan semua elemen dengan ID "uang"
var uangElements = document.querySelectorAll('[id="filter_saldo"]');

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
