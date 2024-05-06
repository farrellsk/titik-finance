<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('detail_kasbank', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payment');
            $table->unsignedBigInteger('id_transaksi');
            $table->datetime('tanggal');
            $table->string('kategori');
            $table->string('pelanggan');
            $table->integer('tanggal_tempo');
            $table->unsignedBigInteger('total');
            $table->timestamps();

            $table->foreign('id_transaksi')->references('id')->on('transaction')->onDelete('cascade');
            $table->foreign('payment')->references('id_kas')->on('akun_kas')->onDelete('cascade');
        });

        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_kasbank');

    }
};
