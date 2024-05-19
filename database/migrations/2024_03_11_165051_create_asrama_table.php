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

        Schema::create('asrama', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('nama');
            $table->string('jenis_kelamin');
            $table->tinyInteger('kapasitas_per_kamar');
            $table->string('nama_pemilik')->nullable();
            $table->string('kontak_pemilik')->nullable();
            $table->string('kepemilikan_gedung')->nullable();
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
        Schema::dropIfExists('asrama');
    }
};
