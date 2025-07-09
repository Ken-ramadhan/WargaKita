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
        Schema::create('rukun_tetangga', function (Blueprint $table) {
            $table->id();
            $table->char('nik', 16)->unique();
            $table->string('nomor_rt');
            $table->string('nama_ketua_rt');
            $table->date('mulai_menjabat');
            $table->date('akhir_jabatan');
            $table->string('alamat_rt')->nullable();
            $table->foreign('id_rw')->references('id')->on('rw')->onDelete('cascade');
            $table->unsignedBigInteger('id_rw');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rukun_tetangga');
    }
};
