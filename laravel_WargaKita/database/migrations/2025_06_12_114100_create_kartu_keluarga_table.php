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
        Schema::create('kartu_keluarga', function (Blueprint $table) {
            $table->char('no_kk', 16)->primary()->unique();
            $table->string('no_registrasi')->unique(); // Nomor registrasi unik untuk setiap KK
            $table->text('alamat');
            $table->unsignedBigInteger('id_rt')->nullable(); // Foreign key ke tabel rukun_tetangga
            $table->unsignedBigInteger('id_rw')->nullable(); // Foreign key ke tabel r
            $table->foreign('id_rt')->references('id')->on('rukun_tetangga')->onDelete('set null');
            $table->foreign('id_rw')->references('id')->on('rw')->onDelete('set null');
            $table->string('kelurahan');
            $table->string('kecamatan');
            $table->string('kabupaten');
            $table->string('provinsi');
            $table->string('kode_pos');
            $table->date('tgl_terbit');
            $table->enum('kategori_iuran', ['kampung', 'kavling', 'kost', 'kantor', 'kontrakan', 'umkm']);
            $table->string('instansi_penerbit')->nullable(); // Contoh: 'Dinas Kependudukan dan Pencatatan Sipil'
            $table->string('kabupaten_kota_penerbit')->nullable(); // Contoh: 'Kota Bandung'
            $table->string('nama_kepala_dukcapil')->nullable(); // Nama lengkap Kepala Dinas Dukcapil
            $table->string('nip_kepala_dukcapil')->nullable(); // NIP Kepala Dinas Dukcapil
            $table->string('foto_kk')->nullable(); // Path atau URL ke foto Kartu Keluarga
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kartu_keluarga');
    }
};
