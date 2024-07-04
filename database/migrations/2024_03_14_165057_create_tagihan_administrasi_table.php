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

        Schema::create('tagihan_administrasi', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('administrasi_id')->nullable()->references('id')->on('administrasi')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignUlid('user_id')->nullable()->references('id')->on('users')->cascadeOnUpdate()->nullOnDelete();
            $table->integer('jumlah_tagihan');
            $table->string('status_tagihan');
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
        Schema::dropIfExists('tagihan_administrasi');
    }
};
