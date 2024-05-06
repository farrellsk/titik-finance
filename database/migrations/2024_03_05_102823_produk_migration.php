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
        Schema::create('produk', function (Blueprint $table) {
            $table->id();
            $table->string('nama_produk');
            $table->integer('harga_jual');
            $table->integer('harga_beli');
            $table->string('kode_produk')->nullable();
            $table->string('kategori_produk')->nullable();
            $table->integer('total_stok')->nullable();
            $table->integer('minimun_stok')->nullable();
            $table->enum('unit', ['buah', 'pcs']);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
