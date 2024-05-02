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

        Schema::create('pendaftaran', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->unsignedInteger('tahun_pendaftaran');
            $table->json('kontak_panitia')->default([]);
            $table->json('kontak_pengurus')->default([]);
            $table->json('berkas_pendaftaran')->default([]);
            $table->json('indikator_penilaian')->default([]);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftaran');
    }
};
