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
            $table->char('nik', 16)->primary();
            $table->char('no_kk', 16);

            $table->foreign('no_kk')
                ->references('no_kk')
                ->on('kartu_keluarga')
                ->onDelete('restrict')
                ->onUpdate('cascade');
            $table->string('nama');
            $table->enum('jenis_kelamin', ['laki-laki', 'perempuan']);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('agama');
            $table->string('pendidikan');
            $table->string('pekerjaan');
            $table->enum('status_perkawinan', ['belum menikah', 'menikah', 'cerai_hidup', 'cerai_mati']);
            $table->enum('status_hubungan_dalam_keluarga', ['kepala keluarga', 'istri', 'anak']);
            $table->enum('golongan_darah', ['A', 'B', 'AB', 'O']);
            $table->enum('kewarganegaraan', ['WNI', 'WNA']);
            $table->string('nama_ayah');
            $table->string('nama_ibu');
            $table->enum('jenis', ['penduduk', 'pendatang']);
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
