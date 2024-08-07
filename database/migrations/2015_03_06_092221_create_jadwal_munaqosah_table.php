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

        Schema::create('jadwal_munaqosah', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('materi_munaqosah_id')->references('id')->on('materi_munaqosah')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamp('waktu');
            $table->unsignedTinyInteger('maksimal_pendaftar');
            $table->timestamp('batas_awal_pendaftaran')->nullable();
            $table->timestamp('batas_akhir_pendaftaran')->nullable();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_munaqosah');
    }
};
