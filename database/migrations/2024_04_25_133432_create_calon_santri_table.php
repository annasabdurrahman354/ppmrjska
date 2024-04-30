<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up(): void
{
    Schema::table('calon_santri', function (Blueprint $table) {
        $table->ulid('id')->primary();
        $table->foreignUlid('gelombang_pendaftaran_id')->references('id')->on('gelombang_pendaftaran')->cascadeOnUpdate()->cascadeOnDelete();
        $table->string('nama', 96);
        $table->bigInteger('nik')->unique(); // Mengubah tipe data nik menjadi bigInteger
        $table->string('nomor_telepon', 16);
        $table->string('email');
        $table->boolean('jenis_kelamin');
        $table->string('tempat_lahir', 30);
        $table->date('tanggal_lahir');
        $table->string('golongan_darah')->nullable();
        $table->string('ukuran_baju')->nullable();
        $table->string('kewarganegaraan')->nullable();
        $table->string('status_menikah')->nullable();
        $table->string('status_tinggal')->nullable();
        $table->string('status_orangtua')->nullable();
        $table->unsignedTinyInteger('anak_ke');
        $table->unsignedTinyInteger('jumlah_saudara');
        $table->string('pendidikan_terakhir')->nullable();
        $table->string('program_studi', 96)->nullable();
        $table->string('universitas', 96)->nullable();
        $table->unsignedSmallInteger('angkatan_kuliah')->nullable();
        $table->string('alamat');
        $table->unsignedBigInteger('provinsi_id');
        $table->foreign('provinsi_id')->references('id')->on('provinsi')->onDelete('cascade');
        $table->unsignedBigInteger('kota_id');
        $table->foreign('kota_id')->references('id')->on('kota')->onDelete('cascade');
        $table->unsignedBigInteger('kecamatan_id');
        $table->foreign('kecamatan_id')->references('id')->on('kecamatan')->onDelete('cascade');
        $table->unsignedBigInteger('kelurahan_id');
        $table->foreign('kelurahan_id')->references('id')->on('kelurahan')->onDelete('cascade');
        $table->string('asal_daerah', 96);
        $table->string('asal_desa', 96);
        $table->string('asal_kelompok', 96);
        $table->string('mulai_mengaji')->nullable();
        $table->string('nama_ayah', 96);
        $table->string('alamat_ayah')->nullable();
        $table->string('nomor_telepon_ayah', 16)->nullable();
        $table->string('pekerjaan_ayah', 96)->nullable();
        $table->string('dapukan_ayah', 96)->nullable();
        $table->string('nama_ibu', 96);
        $table->string('alamat_ibu')->nullable();
        $table->string('nomor_telepon_ibu', 16)->nullable();
        $table->string('pekerjaan_ibu', 96)->nullable();
        $table->string('dapukan_ibu', 96)->nullable();
        $table->timestamps();
        $table->softDeletes();
    });
}


    /**
     * Reverse the migrations.
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('calon_santri');
    }
};
