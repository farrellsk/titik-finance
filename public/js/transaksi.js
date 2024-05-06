$('#payment-method').select2({});
//INI SELECT2 PRODUK DI TRANSAKSI
$('.pp1').select2({
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
    var $this = $(this); // Simpan konteks elemen yang dipilih

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


//INI SELECT2 KONTAK DI TRANSAKSI
var tipe = $('.kk1').attr('data-tipe');
var selectedOption = {
    id: $('.kk1').attr('data-id'),
    text: $('.kk1').attr('data-name')
};
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

//