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

        Schema::create('presensi_kelas', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('jurnal_kelas_id')->references('id')->on('jurnal_kelas')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignUlid('user_id')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('status_kehadiran')->default('alpa');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi_kelas');
    }
};
