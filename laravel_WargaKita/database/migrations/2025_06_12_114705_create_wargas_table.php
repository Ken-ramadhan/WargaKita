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
        Schema::create('wargas', function (Blueprint $table) {
            $table->char('nik', 16)->primary()->unique();
            $table->char('no_kk', 16); // atau tipe lain sesuai dengan kolom aslinya
            $table->foreign('no_kk')->references('no_kk')->on('kartu_keluargas')->onDelete('cascade');

            $table->string('nama');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wargas');
    }
};
