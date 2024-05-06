<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
        CREATE VIEW jurnal_view AS
        SELECT
            t.id AS id_transaksi,
            t.tanggal AS tanggal,
            NULL AS kontak,
            t.notes AS deskripsi,
            'Rp. ' || ROUND(SUM(CASE WHEN t.tipe = 'pemasukan' THEN ti.total ELSE 0 END), 0) AS terima,
            'Rp. ' || ROUND(SUM(CASE WHEN t.tipe = 'pengeluaran' THEN ti.total ELSE 0 END), 0) AS kirim,
            'Rp. ' || ROUND(SUM(u.saldo_setelahnya), 0) AS saldo,
            t.status AS status
        FROM
            transaction t
            LEFT JOIN update_saldo_kasbank_histories u ON u.id_item = t.id
            LEFT JOIN transaction_item ti ON ti.id_transaksi = t.id
        GROUP BY
            t.id, t.tanggal, t.notes, t.status

        UNION ALL

        SELECT
            b.kd_akun_biaya AS id_transaksi,
            b.created_at AS tanggal,
            b.nama_penerima AS kontak,
            b.nama_akun_biaya AS deskripsi,
            NULL AS terima,
            'Rp. ' || ROUND(SUM(b.jumlah), 0) AS kirim,
            'Rp. ' || ROUND(SUM(u.saldo_setelahnya), 0) AS saldo,
            NULL AS status
        FROM
            akun_biaya b
            LEFT JOIN update_saldo_kasbank_histories u ON u.id_item = b.kd_akun_biaya
        GROUP BY
            b.kd_akun_biaya, b.created_at, b.nama_penerima, b.nama_akun_biaya;


        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXITS v_laporan_jurnal");
    }
};
