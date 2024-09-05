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
        Schema::disableForeignKeyConstraints();

        Schema::create('plot_kelompok_kegiatan_semester', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('tahun_ajaran');
            $table->string('semester');
            $table->foreignUlid('jenis_kegiatan_id')->references('id')->on('jenis_kegiatan')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('kelompok_kegiatan', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('plot_kegiatan_semester_id')->references('id')->on('plot_kegiatan_semester')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('jenis_kelamin');
            $table->string('nama_kelompok');
            $table->foreignUlid('ketua_kelompok_id')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('anggota_kelompok_kegiatan', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('kelompok_kegiatan_id')->references('id')->on('kelompok_kegiatan')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignUlid('user_id')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
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
        Schema::dropIfExists('anggota_kelompok_kegiatan');
        Schema::dropIfExists('kelompok_kegiatan');
        Schema::dropIfExists('plot_kelompok_kegiatan_semester');
    }
};
