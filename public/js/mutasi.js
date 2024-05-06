var jumlahMutasi = document.querySelector('[name="jumlah_mutasi"]');
var biayaLayanan = document.querySelector('[name="biaya_layanan"]');

jumlahMutasi.addEventListener('input', function (e) {
    this.value = formatRupiah(e.target.value, 'Rp. ');
});

biayaLayanan.addEventListener('input', function (e) {
    this.value = formatRupiah(e.target.value, 'Rp. ');
});

function formatRupiah(a, p) {
    var number_string = a.toString().replace(/[^,\d]/g, ''),
        sp = number_string.split(','),
        us = sp[0].length % 3,
        rp = sp[0].substr(0, us),
        rb = sp[0].substr(us).match(/\d{3}/gi);

    if (rb) {
        separator = us ? '.' : '';
        rp += separator + rb.join('.');
    }

    rp = sp[1] != undefined ? rp + ',' + sp[1] : rp;
    return p == undefined ? rp : (rp ? 'Rp. ' + rp : '');
}
