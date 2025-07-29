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
            $table->char('nik', 16)->primary(); // Nomor Induk Kependudukan (untuk WNI). Tetap primary key.
            $table->char('no_kk', 16); // Foreign key ke tabel kartu_keluarga

            $table->foreign('no_kk')
                ->references('no_kk')
                ->on('kartu_keluarga')
                ->onUpdate('cascade')
                ->onDelete('cascade'); // Menggunakan onUpdate('cascade') untuk update no_kk jika ada perubahan.
            // Jika perlu onDelete, pertimbangkan apakah data warga akan ikut terhapus jika KK terhapus.
            // Biasanya, onDelete('restrict') atau onDelete('set null') lebih aman untuk data kependudukan.

            $table->string('nama');
            $table->enum('jenis_kelamin', ['laki-laki', 'perempuan']);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('agama');
            $table->string('pendidikan');
            $table->string('pekerjaan');
            $table->enum('status_perkawinan', ['belum menikah', 'menikah', 'cerai_hidup', 'cerai_mati']);
            $table->enum('status_hubungan_dalam_keluarga', ['kepala keluarga', 'istri', 'anak']); // Menambahkan opsi lain

            $table->enum('golongan_darah', ['A', 'B', 'AB', 'O',])->nullable(); // Menambahkan 'Tidak Tahu' & nullable
            $table->enum('kewarganegaraan', ['WNI', 'WNA']);

            // --- Penambahan Kolom untuk WNA ---
            $table->string('no_paspor')->nullable()->unique(); // Nomor Paspor. Unique jika setiap paspor unik per warga.
            $table->date('tgl_terbit_paspor')->nullable();
            $table->date('tgl_berakhir_paspor')->nullable();

            $table->string('no_kitas')->nullable()->unique(); // Nomor KITAS. Unique jika setiap KITAS unik per warga.
            $table->date('tgl_terbit_kitas')->nullable();
            $table->date('tgl_berakhir_kitas')->nullable();

            $table->string('no_kitap')->nullable()->unique(); // Nomor KITAP. Unique jika setiap KITAP unik per warga.
            $table->date('tgl_terbit_kitap')->nullable();
            $table->date('tgl_berakhir_kitap')->nullable();
            // --- Akhir Penambahan ---

            $table->string('nama_ayah');
            $table->string('nama_ibu');
            $table->enum('status_warga', ['penduduk', 'pendatang']); // 'penduduk' untuk yang tinggal permanen/lama, 'pendatang' untuk sementara.
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
