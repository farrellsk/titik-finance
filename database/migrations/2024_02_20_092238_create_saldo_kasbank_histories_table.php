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
        Schema::create('update_saldo_kasbank_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_item');
            $table->unsignedBigInteger('id_transaksi');
            $table->integer('saldo_sebelumnya');
            $table->integer('saldo_setelahnya');
            $table->string('jenis_transaksi');
            $table->timestamps();

            $table->foreign('id_transaksi')->references('id')->on('transaction')->onDelete('cascade');
            $table->foreign('id_item')->references('id')->on('transaction_item')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('update_saldo_kasbank_histories');
    }
};
