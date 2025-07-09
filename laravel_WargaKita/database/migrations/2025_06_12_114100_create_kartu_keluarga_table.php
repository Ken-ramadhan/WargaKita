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
            $table->text('alamat');
            $table->foreignId('id_rt')->constrained('rukun_tetangga')->onDelete('restrict');
            $table->foreignId('id_rw')->constrained('rw')->onDelete('restrict');
            $table->string('kelurahan');
            $table->string('kecamatan');
            $table->string('kabupaten');
            $table->string('provinsi');
            $table->string('kode_pos');
            $table->date('tgl_terbit');
            $table->enum('golongan', ['kampung','kavling','kost','kantor','kontrakan','umkm']);
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
