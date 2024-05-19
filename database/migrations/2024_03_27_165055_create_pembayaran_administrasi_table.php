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

        Schema::create('pembayaran_administrasi', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tagihan_administrasi_id')->nullable()->references('id')->on('tagihan_administrasi')->cascadeOnUpdate()->nullOnDelete();
            $table->date('tanggal_pembayaran');
            $table->integer('jumlah_pembayaran');
            $table->string('pembayaran_melalui'); //DMC-Pasus, Bendahara PPM, Transfer Rekening
            $table->string('catatan_bendahara');
            $table->string('status_verifikasi');
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
        Schema::dropIfExists('pembayaran_administrasi');
    }
};
