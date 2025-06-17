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
        Schema::create('tagihans', function (Blueprint $table) {
            $table->id();
            $table->char('no_kk', 16);
            $table->foreign('no_kk')->references('no_kk')->on('kartu_keluargas')->onDelete('cascade');
            $table->enum('status', ['lunas', 'belum_lunas'])->default('belum_lunas');
            $table->dateTime('tgl_bayar')->nullable();
            $table->foreignId('id_iuran')->constrained('iurans')->onDelete('cascade');
            $table->enum('kategori_pembayaran',['transfer', 'tunai'])->nullable();
            $table->string('bukti_transfer')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tagihans');
    }
};
