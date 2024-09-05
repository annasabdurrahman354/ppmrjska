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

        Schema::create('jurnal_kegiatan', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('jenis_kegiatan_id')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->date('tanggal');
            $table->string('jenis_kelamin');
            $table->string('grup_type');
            $table->string('grup');
            $table->foreignUlid('perekap_id')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurnal_kegiatan');
    }
};
