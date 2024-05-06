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
        Schema::create('view_update_biaya_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('id_tbiaya');
            $table->integer('saldo_sebelumnya');
            $table->integer('saldo_setelahnya');
            $table->date('waktu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('view_update_biaya_history');
    }
};
