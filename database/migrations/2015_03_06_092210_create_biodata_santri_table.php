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
            $table->unsignedSmallInteger('tahun_pendaftaran');
            $table->string('nik', 16)->unique();
            $table->unsignedSmallInteger('kota_lahir_id');
            $table->foreign('kota_lahir_id')->references('id')->on('kota');
            $table->date('tanggal_lahir');
            $table->string('golongan_darah');
            $table->string('ukuran_baju');
            $table->string('pendidikan_terakhir');
            $table->string('program_studi', 96);
            $table->string('universitas', 96);
            $table->unsignedSmallInteger('angkatan_kuliah');
            $table->string('status_kuliah');
            $table->date('tanggal_lulus_kuliah')->nullable();
            $table->string('alamat');
            $table->unsignedBigInteger('kelurahan_id');
            $table->foreign('kelurahan_id')->references('id')->on('kelurahan');
            $table->unsignedBigInteger('kecamatan_id');
            $table->foreign('kecamatan_id')->references('id')->on('kecamatan');
            $table->unsignedSmallInteger('kota_id');
            $table->foreign('kota_id')->references('id')->on('kota');
            $table->unsignedTinyInteger('provinsi_id');
            $table->foreign('provinsi_id')->references('id')->on('provinsi');
            $table->string('asal_kelompok', 96);
            $table->string('asal_desa', 96);
            $table->string('asal_daerah', 96);
            $table->string('mulai_mengaji');
            $table->string('bahasa_makna');
            $table->string('kewarganegaraan');
            $table->string('status_pernikahan');
            $table->string('status_tinggal');
            $table->string('status_orangtua');
            $table->unsignedTinyInteger('anak_nomor');
            $table->unsignedTinyInteger('jumlah_saudara');
            $table->string('nama_ayah', 96);
            $table->string('nomor_telepon_ayah', 16);
            $table->string('pekerjaan_ayah', 96);
            $table->string('dapukan_ayah', 96);
            $table->string('nama_ibu', 96);
            $table->string('nomor_telepon_ibu', 16);
            $table->string('pekerjaan_ibu', 96);
            $table->string('dapukan_ibu', 96);
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
