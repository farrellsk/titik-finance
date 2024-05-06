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
        Schema::create('kategori_biaya', function (Blueprint $table) {
            $table->bigIncrements('id_kategori_biaya');
            $table->string('nama_kategori')->unique();
            $table->integer('jumlah')->default('0');
            $table->enum('status', ['aktif', 'non-aktif']);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategori_biaya');
    }
};
