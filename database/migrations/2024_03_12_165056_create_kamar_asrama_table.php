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

        Schema::create('kamar_asrama', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('asrama_id')->nullable()->references('id')->on('asrama')->cascadeOnUpdate()->nullOnDelete();
            $table->tinyInteger('lantai');
            $table->string('nomor_kamar');
            $table->boolean('status_ketersediaan');
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
        Schema::dropIfExists('kamar_asrama');
    }
};
