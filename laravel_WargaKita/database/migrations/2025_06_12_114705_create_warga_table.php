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
        Schema::create('warga', function (Blueprint $table) {
            $table->char('nik', 16)->primary(); // Sudah cukup, tidak perlu ->unique() lagi
            $table->char('no_kk', 16);
            $table->foreign('no_kk')
                ->references('no_kk')
                ->on('kartu_keluarga')
                ->onDelete('restrict')
                ->onUpdate('cascade');
            $table->string('nama');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warga');
    }
};
