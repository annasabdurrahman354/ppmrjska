<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('biodata_santri', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('user_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->unsignedInteger('tahun_pendaftaran');

            $table->string('nik', 16);
            $table->unsignedSmallInteger('tempat_lahir_id')->nullable();
            $table->foreign('tempat_lahir_id')->references('id')->on('kota')->nullOnDelete();
            $table->date('tanggal_lahir')->nullable();
            $table->string('kewarganegaraan')->nullable();
            $table->string('golongan_darah')->nullable();
            $table->string('ukuran_baju')->nullable();

            $table->string('pendidikan_terakhir')->nullable();
            $table->string('program_studi')->nullable();
            $table->string('universitas')->nullable();
            $table->unsignedSmallInteger('angkatan_kuliah')->nullable();
            $table->string('status_kuliah')->nullable();
            $table->date('tanggal_lulus_kuliah')->nullable();

            $table->string('alamat')->nullable();
            $table->unsignedTinyInteger('provinsi_id')->nullable();
            $table->foreign('provinsi_id')->references('id')->on('provinsi')->nullOnDelete();
            $table->unsignedSmallInteger('kota_id')->nullable();
            $table->foreign('kota_id')->references('id')->on('kota')->nullOnDelete();
            $table->unsignedBigInteger('kecamatan_id')->nullable();
            $table->foreign('kecamatan_id')->references('id')->on('kecamatan')->nullOnDelete();
            $table->unsignedBigInteger('kelurahan_id')->nullable();
            $table->foreign('kelurahan_id')->references('id')->on('kelurahan')->nullOnDelete();

            $table->string('asal_kelompok', 96);
            $table->string('asal_desa', 96);
            $table->string('asal_daerah', 96);
            $table->string('mulai_mengaji')->nullable();
            $table->string('bahasa_makna')->nullable();

            $table->string('status_pernikahan')->nullable();
            $table->string('status_tinggal')->nullable();
            $table->string('status_orangtua')->nullable();
            $table->unsignedTinyInteger('jumlah_saudara')->nullable();
            $table->unsignedTinyInteger('anak_nomor')->nullable();

            $table->string('nama_ayah')->nullable();
            $table->string('nomor_telepon_ayah', 16)->nullable();
            $table->string('pekerjaan_ayah')->nullable();
            $table->string('dapukan_ayah')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->string('nomor_telepon_ibu', 16)->nullable();
            $table->string('pekerjaan_ibu')->nullable();
            $table->string('dapukan_ibu')->nullable();
            $table->string('nama_wali')->nullable();
            $table->string('nomor_telepon_wali', 16)->nullable();
            $table->string('pekerjaan_wali')->nullable();
            $table->string('dapukan_wali')->nullable();
            $table->string('hubungan_wali')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biodata_santri');
    }
};
