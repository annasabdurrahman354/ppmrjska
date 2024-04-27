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
            $table->json('biaya_kamar');
            $table->string('pemilik')->nullable();
            $table->string('kontak_pemilik')->nullable();
            $table->string('status_kepemilikan')->nullable();
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
