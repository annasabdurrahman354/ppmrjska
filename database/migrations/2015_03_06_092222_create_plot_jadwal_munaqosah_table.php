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

        Schema::create('plot_jadwal_munaqosah', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('jadwal_munaqosah_id')->references('id')->on('jadwal_munaqosah')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignUlid('user_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->boolean('status_terlaksana')->default(false);
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plot_jadwal_munaqosah');
    }
};
