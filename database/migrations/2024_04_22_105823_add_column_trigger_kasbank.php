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
        Schema::table('update_saldo_kasbank_histories', function (Blueprint $table) {
            $table->unsignedBigInteger('id_mutasi');
        });

        Schema::table('update_saldo_kasbank_histories', function (Blueprint $table){
            $table->unsignedBigInteger('id_item')->nullable()->change();
        });

        Schema::table('update_saldo_kasbank_histories', function (Blueprint $table){
            $table->unsignedBigInteger('id_transaksi')->nullable()->change();
        });

        

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('update_saldo_kasbank_histories', function (Blueprint $table){
            $table->unsignedBigInteger('id_item')->change();
        });
        Schema::table('update_saldo_kasbank_histories', function (Blueprint $table){
            $table->unsignedBigInteger('id_transaksi')->change();
        });
    }
};
