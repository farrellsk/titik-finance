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
        Schema::table('detail_kasbank', function (Blueprint $table){
            $table->date('tanggal_tempo')->nullable()->change();
        });
        Schema::table('detail_kasbank', function (Blueprint $table){
            $table->date('tanggal')->change();
        });

        Schema::table('detail_kasbank', function (Blueprint $table){
            $table->unsignedBigInteger('id_transaksi')->nullable()->change();
        });



    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_kasbank', function (Blueprint $table){
            $table->integer('tanggal_tempo')->change();
        });
        Schema::table('detail_kasbank', function (Blueprint $table){
            $table->datetime('tanggal')->change();
        });
        Schema::table('detail_kasbank', function (Blueprint $table){
            $table->unsignedBigInteger('id_transaksi')->change();
        });
     

    }
};
