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
        Schema::table('akun_biaya', function (Blueprint $table){
            $table->bigInteger('jumlah')->change();
        });

        Schema::table('akun_kas', function (Blueprint $table){
            $table->bigInteger('saldo_awal')->change();
        });
        
        Schema::table('kategori_biaya', function (Blueprint $table){
            $table->bigInteger('jumlah')->change();
        });

        Schema::table('produk', function (Blueprint $table){
            $table->bigInteger('harga_jual')->change();
            $table->bigInteger('harga_beli')->change();
        });

        Schema::table('transaction_item', function (Blueprint $table){
            $table->bigInteger('amount')->change();
            $table->bigInteger('total')->change();
        });

        Schema::table('update_saldo_kasbank_histories', function (Blueprint $table){
            $table->bigInteger('saldo_sebelumnya')->change();
            $table->bigInteger('saldo_setelahnya')->change();
        });

        Schema::table('view_update_biaya_history', function (Blueprint $table){
            $table->bigInteger('saldo_sebelumnya')->change();
            $table->bigInteger('saldo_setelahnya')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('akun_biaya', function (Blueprint $table){
            $table->integer('jumlah')->change();
        });

        Schema::table('akun_kas', function (Blueprint $table){
            $table->integer('saldo_awal')->change();
        });
        
        Schema::table('kategori_biaya', function (Blueprint $table){
            $table->integer('jumlah')->change();
        });

        Schema::table('produk', function (Blueprint $table){
            $table->integer('harga_jual')->change();
            $table->integer('harga_beli')->change();
        });

        Schema::table('transaction_item', function (Blueprint $table){
            $table->integer('amount')->change();
            $table->integer('total')->change();
        });

        Schema::table('update_saldo_kasbank_histories', function (Blueprint $table){
            $table->integer('saldo_sebelumnya')->change();
            $table->integer('saldo_setelahnya')->change();
        });

        Schema::table('view_update_biaya_history', function (Blueprint $table){
            $table->integer('saldo_sebelumnya')->change();
            $table->integer('saldo_setelahnya')->change();
        });
    }
};
