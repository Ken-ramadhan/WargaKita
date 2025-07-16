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
        Schema::create('tagihan', function (Blueprint $table) {
            $table->id();
            // Kolom-kolom yang ada di fungsionalitas "tagihan manual"
            $table->string('nama'); // Ditambahkan
            $table->decimal('nominal', 10, 2); // Ditambahkan   
            $table->date('tgl_tagih'); // Ditambahkan
            $table->date('tgl_tempo'); // Ditambahkan
            $table->enum('jenis', ['otomatis', 'manual'])->default('manual'); // Ditambahkan

            $table->char('no_kk', 16);
            $table->foreign('no_kk')->references('no_kk')->on('kartu_keluarga')->onDelete('restrict');

            // Kolom-kolom dari migrasi Anda
            $table->enum('status_bayar', ['sudah_bayar', 'belum_bayar'])->default('belum_bayar'); // Diubah dari 'status' ke 'status_bayar'
            $table->dateTime('tgl_bayar')->nullable();
            $table->foreignId('id_iuran')->constrained('iuran')->onDelete('cascade');
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
        Schema::dropIfExists('tagihan');
    }
};
