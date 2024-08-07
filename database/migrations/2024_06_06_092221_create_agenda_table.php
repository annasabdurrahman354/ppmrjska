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

        Schema::create('agenda', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('nama');
            $table->string('deskripsi');
            $table->foreignId('kategori_id')->nullable()->references('id')->on('kategori')->nullOnDelete();
            $table->date('tanggal_awal');
            $table->date('tanggal_akhir');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agenda');
    }
};
