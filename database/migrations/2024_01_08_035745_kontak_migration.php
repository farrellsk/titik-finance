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
        Schema::create('kontak', function (Blueprint $table) {
            $table->bigIncrements('id_kontak');
            $table->unsignedBigInteger('id_user');
            $table->string('kode_kontak');
            $table->string('nama_kontak');
            $table->string('tipe_kontak');
            $table->string('no_telp')->nullable();
            $table->string('alamat')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kontak');
    }
};
