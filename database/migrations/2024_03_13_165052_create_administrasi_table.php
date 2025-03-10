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

        Schema::create('administrasi', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('jenis_administrasi');
            $table->string('nama_administrasi');
            $table->string('tahun_ajaran');
            $table->string('jenis_tagihan');
            $table->string('periode_tagihan');
            $table->json('sasaran');
            $table->integer('nominal_tagihan');
            $table->date('batas_awal_pembayaran');
            $table->date('batas_akhir_pembayaran');
            $table->foreignUlid('rekening_id')->nullable()->references('id')->on('rekening')->cascadeOnUpdate()->nullOnDelete();
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
        Schema::dropIfExists('administrasi');
    }
};
