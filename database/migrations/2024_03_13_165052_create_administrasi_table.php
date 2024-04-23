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
            $table->string('tahun_ajaran');
            $table->date('batas_awal_pembayaran');
            $table->date('batas_akhir_pembayaran');
            $table->integer('biaya_administrasi');
            $table->integer('biaya_tambahan_santri_baru');
            $table->string('nama_bank');
            $table->string('nomor_rekening');
            $table->string('nama_pemilik_rekening');
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
