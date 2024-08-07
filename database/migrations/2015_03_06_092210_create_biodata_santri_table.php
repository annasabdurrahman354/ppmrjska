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

            $table->string('nik', 16)->unique();
            $table->unsignedSmallInteger('tempat_lahir_id')->nullable();
            $table->foreign('tempat_lahir_id')->references('id')->on('kota')->nullOnDelete();
            $table->date('tanggal_lahir');
            $table->string('kewarganegaraan');
            $table->string('golongan_darah');
            $table->string('ukuran_baju');

            $table->string('pendidikan_terakhir');
            $table->string('program_studi');
            $table->string('universitas');
            $table->unsignedSmallInteger('angkatan_kuliah');
            $table->string('status_kuliah');
            $table->date('tanggal_lulus_kuliah')->nullable();

            $table->string('alamat');
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
            $table->string('mulai_mengaji');
            $table->string('bahasa_makna');

            $table->string('status_pernikahan');
            $table->string('status_tinggal');
            $table->string('status_orangtua');
            $table->unsignedTinyInteger('jumlah_saudara');
            $table->unsignedTinyInteger('anak_nomor');

            $table->string('nama_ayah');
            $table->string('nomor_telepon_ayah', 16)->nullable();
            $table->string('pekerjaan_ayah')->nullable();
            $table->string('dapukan_ayah')->nullable();
            $table->string('nama_ibu');
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
