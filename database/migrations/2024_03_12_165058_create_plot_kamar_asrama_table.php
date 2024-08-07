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

        Schema::create('plot_kamar_asrama', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('tahun_ajaran');
            $table->foreignUlid('kamar_asrama_id')->references('id')->on('kamar_asrama')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignUlid('user_id')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
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
        Schema::dropIfExists('plot_kamar_asrama');
    }
};
