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
            $table->string('slug')->unique();
            $table->string('penghuni');
            $table->string('alamat')->nullable();
            $table->string('deskripsi')->nullable();
            //$table->json('berkas_foto')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->tinyInteger('kapasitas_per_kamar');
            $table->tinyInteger('kapasitas_total');
            $table->string('kepemilikan_gedung')->nullable();
            $table->string('nama_pemilik')->nullable();
            $table->string('nomor_telepon_pemilik')->nullable();
            $table->integer('biaya_asrama_tahunan')->default(0);
            $table->string('pembebanan_biaya_asrama');
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
