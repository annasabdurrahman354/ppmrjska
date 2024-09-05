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

        Schema::create('presensi_kegiatan', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('jurnal_kegiatan_id')->references('id')->on('jurnal_kegiatan')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignUlid('user_id')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->boolean('status_kehadiran')->default(false);
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi_kegiatan');
    }
};
