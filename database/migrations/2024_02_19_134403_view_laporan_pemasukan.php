<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
        CREATE VIEW laporan_pemasukan AS
            SELECT
                DATE_FORMAT(ts.tanggal, '%d-%m-%Y') AS tanggal,
                ts.tipe AS tipe,
                ts.notes AS notes,
                ts.status AS status,
                SUM(ti.total) AS total
            FROM
                transaction AS ts
            INNER JOIN
                transaction_item AS ti ON ts.id = ti.id_transaksi
            WHERE
                ts.tipe = 'pemasukan'
            GROUP BY
                ts.id, ts.tanggal, ts.tipe, ts.notes, ts.status
            ORDER BY
                ts.tanggal
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS laporan_pemasukan');
    }
};