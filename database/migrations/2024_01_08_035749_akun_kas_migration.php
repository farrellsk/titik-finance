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
        Schema::create('akun_kas', function (Blueprint $table) {
            $table->bigIncrements('id_kas');
            $table->string('kd_kas');
            $table->string('payment');
            $table->string('nama_akun');
            $table->integer('saldo_awal');
            $table->string('kategori')->nullable();
            $table->string('nama_rekening')->nullable();
            $table->bigInteger('no_rekening')->nullable()->unique();
            $table->rememberToken();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akun_kas');
    }
};
