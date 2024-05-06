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
        Schema::create('mutasi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('akun_asal');
            $table->unsignedBigInteger('akun_tujuan');
            $table->bigInteger('jumlah_mutasi');
            $table->integer('biaya_layanan');
            $table->string('keterangan')->nullable();
            $table->string('lampiran')->nullable();
            $table->dateTime('waktu')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutasi');
    }
};
