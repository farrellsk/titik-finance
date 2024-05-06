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
        Schema::create('akun_biaya', function (Blueprint $table) {
            $table->bigIncrements('id_akun_biaya');
            $table->unsignedBigInteger('id_kategori_biaya');
            $table->string('kd_akun_biaya')->default('AKB000');
            $table->string('nama_akun_biaya');
            $table->string('nama_penerima');
            $table->string('metode_pembayaran');
            $table->string('kategori_akun_biaya');
            $table->integer('jumlah');
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('id_kategori_biaya')->references('id_kategori_biaya')->on('kategori_biaya')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akun_biaya');
    }
};
