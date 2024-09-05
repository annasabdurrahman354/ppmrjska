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

        Schema::create('jenis_kegiatan', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('nama');
            $table->string('sistem_presensi');
            $table->unsignedBigInteger('pemilik_proker_id')->nullable()->references('id')->on('roles')->cascadeOnUpdate()->nullOnDelete();
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
        Schema::dropIfExists('jenis_kegiatan');
    }
};
