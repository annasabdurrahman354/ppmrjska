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

        Schema::create('materi_munaqosah', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('kelas');
            $table->unsignedTinyInteger('semester');
            $table->string('tahun_ajaran');
            $table->string('jenis_materi');
            $table->json('materi')->nullable();
            $table->string('detail')->nullable();
            $table->json('hafalan')->nullable();
            $table->json('indikator_materi')->nullable();
            $table->json('indikator_hafalan')->nullable();
            $table->foreignUlid('dewan_guru_id')->nullable()->references('id')->on('dewan_guru')->cascadeOnUpdate()->nullOnDelete();
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
        Schema::dropIfExists('materi_munaqosah');
    }
};
